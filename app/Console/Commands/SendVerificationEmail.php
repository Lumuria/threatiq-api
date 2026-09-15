<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail extends Command
{
    protected $signature = 'verification:email
        {email : Recipient email}
        {code : 6-digit verification code}
        {--reset : Send as password-reset email}';

    protected $description = 'Send a ThreatIQ verification or reset code email';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $code = (string) $this->argument('code');
        $isReset = (bool) $this->option('reset');

        $subject = $isReset
            ? 'ThreatIQ - Password Reset Code'
            : 'ThreatIQ - Email Verification Code';

        $body = $isReset
            ? "Your ThreatIQ password reset code is: {$code}\n\nThis code will expire in 10 minutes."
            : "Your ThreatIQ verification code is: {$code}\n\nThis code will expire in 10 minutes.";

        try {
            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });

            $this->info('sent');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            report($e);
            return self::FAILURE;
        }
    }
}
