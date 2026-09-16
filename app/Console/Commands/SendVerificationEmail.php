<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
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
            if ($this->sendViaGmailWebhook($email, $subject, $body)) {
                $this->info('sent-gmail-webhook');
                return self::SUCCESS;
            }

            if ($this->sendViaResend($email, $subject, $body)) {
                $this->info('sent-resend');
                return self::SUCCESS;
            }

            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            });

            $this->info('sent-mail');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            report($e);
            return self::FAILURE;
        }
    }

    private function sendViaGmailWebhook(string $email, string $subject, string $body): bool
    {
        $webhook = env('MAIL_HTTP_WEBHOOK');
        $secret = env('MAIL_HTTP_SECRET');

        if (!$webhook || !$secret) {
            return false;
        }

        $response = Http::timeout(20)
            ->acceptJson()
            ->asJson()
            ->post($webhook, [
                'secret' => $secret,
                'to' => $email,
                'subject' => $subject,
                'body' => $body,
                'fromName' => env('MAIL_FROM_NAME', 'ThreatIQ'),
                'replyTo' => env('MAIL_REPLY_TO', 'threatiqsy@gmail.com'),
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'Gmail webhook failed: '.$response->status().' '.$response->body()
            );
        }

        $json = $response->json();
        if (is_array($json) && array_key_exists('ok', $json) && !$json['ok']) {
            throw new \RuntimeException(
                'Gmail webhook rejected: '.($json['error'] ?? 'unknown')
            );
        }

        return true;
    }

    private function sendViaResend(string $email, string $subject, string $body): bool
    {
        $apiKey = env('RESEND_API_KEY');

        if (!$apiKey) {
            return false;
        }

        $from = env('MAIL_FROM_ADDRESS', 'onboarding@resend.dev');
        $fromName = env('MAIL_FROM_NAME', 'ThreatIQ');

        $payload = [
            'from' => "{$fromName} <{$from}>",
            'to' => [$email],
            'subject' => $subject,
            'text' => $body,
        ];

        $replyTo = env('MAIL_REPLY_TO');
        if ($replyTo) {
            $payload['reply_to'] = $replyTo;
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(15)
            ->post('https://api.resend.com/emails', $payload);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'Resend failed: '.$response->status().' '.$response->body()
            );
        }

        return true;
    }
}
