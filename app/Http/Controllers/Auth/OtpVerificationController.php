<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function show(Request $request, EmailVerificationService $emailVerificationService): View|RedirectResponse
    {
        $user = $this->getPendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.otp-verification', [
            'maskedEmail' => $emailVerificationService->maskEmail($user->email),
        ]);
    }

    public function verify(Request $request, EmailVerificationService $emailVerificationService): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $this->getPendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $emailVerificationService->verifyCode($user, $request->otp)) {
            return back()->withErrors([
                'otp' => 'The verification code is invalid or expired.',
            ]);
        }

        Auth::login($user, (bool) $request->session()->pull('auth.pending_remember', false));
        $request->session()->forget('auth.pending_verification_user_id');
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function resend(Request $request, EmailVerificationService $emailVerificationService): RedirectResponse
    {
        $user = $this->getPendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        $emailVerificationService->sendVerificationCode($user, true);

        return back()->with('status', 'A new verification code has been sent to your email address.');
    }

    protected function getPendingUser(Request $request): ?User
    {
        $userId = $request->session()->get('auth.pending_verification_user_id');

        if (! $userId) {
            return null;
        }

        return User::find($userId);
    }
}
