<?php

namespace Database\Seeders;

use App\Models\EmailVerificationCode;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Database\Seeder;

class CleanupTestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $emails = [
            'alfaouremahmoud@gmail.com',
            'kresmiosition50@gmail.com',
        ];

        foreach ($emails as $email) {
            $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();

            if (!$user) {
                continue;
            }

            EmailVerificationCode::where('user_id', $user->id)->delete();
            PasswordResetCode::where('user_id', $user->id)->delete();
            $user->tokens()->delete();
            $user->delete();
        }
    }
}
