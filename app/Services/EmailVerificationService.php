<?php

namespace App\Services;

use App\Mail\EmailVerificationCodeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailVerificationService
{
    public function sendVerificationCode(User $user, bool $forceResend = false): bool
    {
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('expires_at', '>=', now())
            ->first();

        if ($verificationCode && ! $forceResend) {
            return false;
        }

        $code = (string) random_int(100000, 999999);

        EmailVerificationCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'email' => $user->email,
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $code));

        return true;
    }

    public function verifyCode(User $user, string $code): bool
    {
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $code)
            ->where('expires_at', '>=', now())
            ->first();

        if (! $verificationCode) {
            return false;
        }

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        $verificationCode->delete();

        return true;
    }

    public function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);

        return substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 2, 4)) . '@' . $domain;
    }
}
