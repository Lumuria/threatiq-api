<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerificationCode;
use App\Models\PasswordResetCode;
use App\Models\PasswordHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private function sendCodeEmailAsync(string $email, string $code, bool $reset = false): void
    {
        $php = PHP_BINARY ?: 'php';
        $artisan = base_path('artisan');
        $resetFlag = $reset ? ' --reset' : '';

        $command = sprintf(
            '%s %s verification:email %s %s%s > /dev/null 2>&1 &',
            escapeshellarg($php),
            escapeshellarg($artisan),
            escapeshellarg($email),
            escapeshellarg($code),
            $resetFlag
        );

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            // Local Windows fallback (non-blocking best-effort)
            pclose(popen('start /B '.$command, 'r'));
            return;
        }

        exec($command);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'username' => strtolower(
                trim($validated['username'])
            ),
            'email' => strtolower(
                trim($validated['email'])
            ),
            'password' => $validated['password'],
            'role' => 'member',
            'is_admin' => false,
            'email_verified_at' => null,
        ]);

        $code = (string) random_int(100000, 999999);

        EmailVerificationCode::where(
            'user_id',
            $user->id
        )->delete();

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        // Fire-and-forget email so signup never waits on SMTP.
        $this->sendCodeEmailAsync($user->email, $code);

        $payload = [
            'message' => 'Account created. Check your email, or use the on-screen verification code.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => null,
                'role' => $user->role,
                'is_admin' => false,
                'permissions' => [],
            ],
        ];

        if (filter_var(env('SHOW_VERIFICATION_CODE', false), FILTER_VALIDATE_BOOLEAN)) {
            $payload['verification_code'] = $code;
        }

        return response()->json($payload, 201);
    }

    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
            ],

            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        $email = strtolower(
            trim($validated['email'])
        );

        $user = User::where(
            'email',
            $email
        )->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [
                    'User not found.'
                ],
            ]);
        }

        if ($user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => [
                    'Email is already verified.'
                ],
            ]);
        }

        $verification = EmailVerificationCode::where(
            'user_id',
            $user->id
        )
            ->latest()
            ->first();

        if (!$verification) {
            throw ValidationException::withMessages([
                'code' => [
                    'Verification code not found.'
                ],
            ]);
        }

        if (now()->greaterThan($verification->expires_at)) {
            $verification->delete();

            throw ValidationException::withMessages([
                'code' => [
                    'Verification code has expired.'
                ],
            ]);
        }

        if (!Hash::check(
            $validated['code'],
            $verification->code
        )) {
            throw ValidationException::withMessages([
                'code' => [
                    'The verification code is incorrect.'
                ],
            ]);
        }

        $user->email_verified_at = now();
        $user->save();

        $verification->delete();

        $token = $user
            ->createToken('threatiq-web')
            ->plainTextToken;

        return response()->json([
            'message' => 'Email verified successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar
                    ? url(Storage::url($user->avatar))
                    : null,
                'role' => $user->role,
                'is_admin' => (bool) $user->is_admin,
                'permissions' => [],
            ],
            'token' => $token,
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
            ],
        ]);

        $email = strtolower(
            trim($validated['email'])
        );

        $user = User::where(
            'email',
            $email
        )->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [
                    'No account was found with this email address.'
                ],
            ]);
        }

        $code = (string) random_int(100000, 999999);

        PasswordResetCode::where(
            'user_id',
            $user->id
        )->delete();

        PasswordResetCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->sendCodeEmailAsync($user->email, $code, true);

        $payload = [
            'message' => 'Password reset code sent. Check your email, or use the on-screen code.',
        ];

        if (filter_var(env('SHOW_VERIFICATION_CODE', false), FILTER_VALIDATE_BOOLEAN)) {
            $payload['verification_code'] = $code;
        }

        return response()->json($payload);
    }

    public function verifyResetCode(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
            ],

            'code' => [
                'required',
                'digits:6',
            ],
        ]);

        $email = strtolower(
            trim($validated['email'])
        );

        $user = User::where(
            'email',
            $email
        )->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [
                    'No account was found with this email address.'
                ],
            ]);
        }

        $resetCode = PasswordResetCode::where(
            'user_id',
            $user->id
        )
            ->latest()
            ->first();

        if (!$resetCode) {
            throw ValidationException::withMessages([
                'code' => [
                    'Password reset code not found.'
                ],
            ]);
        }

        if (now()->greaterThan($resetCode->expires_at)) {
            $resetCode->delete();

            throw ValidationException::withMessages([
                'code' => [
                    'Password reset code has expired.'
                ],
            ]);
        }

        if (!Hash::check(
            $validated['code'],
            $resetCode->code
        )) {
            throw ValidationException::withMessages([
                'code' => [
                    'The password reset code is incorrect.'
                ],
            ]);
        }

        return response()->json([
            'message' => 'Reset code verified successfully.',
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $login = strtolower(
            trim($validated['login'])
        );

        $user = User::where(function ($query) use ($login) {
            $query
                ->whereRaw(
                    'LOWER(email) = ?',
                    [$login]
                )
                ->orWhereRaw(
                    'LOWER(username) = ?',
                    [$login]
                );
        })->first();

        if (
            !$user ||
            !Hash::check(
                $validated['password'],
                $user->password
            )
        ) {
            throw ValidationException::withMessages([
                'login' => [
                    'The provided credentials are incorrect.'
                ],
            ]);
        }

        if (
            !$user->is_admin &&
            !$user->email_verified_at
        ) {
            throw ValidationException::withMessages([
                'login' => [
                    'Please verify your email before logging in.'
                ],
            ]);
        }

        $token = $user
            ->createToken('threatiq-web')
            ->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar
                    ? url(Storage::url($user->avatar))
                    : null,
                'role' => $user->role,
                'is_admin' => (bool) $user->is_admin,
                'permissions' => $user->is_admin
                    ? ['*']
                    : [],
            ],
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar
                    ? url(Storage::url($user->avatar))
                    : null,
                'role' => $user->role,
                'is_admin' => (bool) $user->is_admin,
                'permissions' => $user->is_admin
                    ? ['*']
                    : [],
            ],
        ]);
    }

    public function users(Request $request)
    {
        $user = $request->user();

        if (!$user->is_admin) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $users = User::select(
            'id',
            'name',
            'username',
            'email',
            'avatar',
            'role',
            'is_admin',
            'email_verified_at',
            'created_at'
        )
            ->orderBy('created_at', 'desc')
            ->get();

        $users->transform(function ($user) {
            $user->avatar = $user->avatar
                ? url(Storage::url($user->avatar))
                : null;

            return $user;
        });

        return response()->json([
            'users' => $users
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar
                    ? url(Storage::url($user->avatar))
                    : null,
                'role' => $user->role,
                'is_admin' => (bool) $user->is_admin,
                'email_verified_at' => $user->email_verified_at,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:users,username,' . $user->id,
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user->name = trim($validated['name']);

        $user->username = strtolower(
            trim($validated['username'])
        );

        $user->email = strtolower(
            trim($validated['email'])
        );

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete(
                    $user->avatar
                );
            }

            $user->avatar = $request
                ->file('avatar')
                ->store('avatars', 'public');
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar
                    ? url(Storage::url($user->avatar))
                    : null,
                'role' => $user->role,
                'is_admin' => (bool) $user->is_admin,
                'email_verified_at' => $user->email_verified_at,
            ],
        ]);
    }

    public function likedPosts(Request $request)
    {
        $user = $request->user();

        $likes = $user->likes()
            ->with([
                'post.user:id,name,username,email'
            ])
            ->latest()
            ->get();

        $posts = $likes
            ->map(function ($like) {
                return $like->post;
            })
            ->filter()
            ->values();

        return response()->json([
            'posts' => $posts,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user = $request->user();

        /*
         * Check the current password
         */
        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {
            return response()->json([
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        /*
         * Prevent using the current password again
         */
        if (Hash::check(
            $request->password,
            $user->password
        )) {
            return response()->json([
                'message' => 'You cannot reuse your current password.',
            ], 422);
        }

        /*
         * Check all previous passwords
         */
        $passwordHistories = PasswordHistory::where(
            'user_id',
            $user->id
        )->get();

        foreach ($passwordHistories as $history) {
            if (Hash::check(
                $request->password,
                $history->password
            )) {
                return response()->json([
                    'message' => 'You cannot reuse a previous password.',
                ], 422);
            }
        }

        /*
         * Save the current password before changing it
         */
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        /*
         * Set the new password
         */
        $user->password = $request->password;
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
            ],

            'code' => [
                'required',
                'digits:6',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $email = strtolower(
            trim($validated['email'])
        );

        $user = User::where(
            'email',
            $email
        )->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [
                    'No account was found with this email address.'
                ],
            ]);
        }

        $resetCode = PasswordResetCode::where(
            'user_id',
            $user->id
        )
            ->latest()
            ->first();

        if (!$resetCode) {
            throw ValidationException::withMessages([
                'code' => [
                    'Password reset code not found.'
                ],
            ]);
        }

        if (now()->greaterThan($resetCode->expires_at)) {
            $resetCode->delete();

            throw ValidationException::withMessages([
                'code' => [
                    'Password reset code has expired.'
                ],
            ]);
        }

        if (!Hash::check(
            $validated['code'],
            $resetCode->code
        )) {
            throw ValidationException::withMessages([
                'code' => [
                    'The password reset code is incorrect.'
                ],
            ]);
        }

        /*
         * Prevent using the current password again
         */
        if (Hash::check(
            $validated['password'],
            $user->password
        )) {
            return response()->json([
                'message' => 'You cannot reuse your current password.',
            ], 422);
        }

        /*
         * Check all previous passwords
         */
        $passwordHistories = PasswordHistory::where(
            'user_id',
            $user->id
        )->get();

        foreach ($passwordHistories as $history) {
            if (Hash::check(
                $validated['password'],
                $history->password
            )) {
                return response()->json([
                    'message' => 'You cannot reuse a previous password.',
                ], 422);
            }
        }

        /*
         * Save the current password before resetting it
         */
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        /*
         * Set the new password
         */
        $user->password = $validated['password'];
        $user->save();

        /*
         * Delete the used reset code
         */
        $resetCode->delete();

        return response()->json([
            'message' => 'Password reset successfully.',
        ]);
    }
}