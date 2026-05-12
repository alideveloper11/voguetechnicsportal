<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\EmailVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.new_login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, EmailVerificationService $emailVerificationService): RedirectResponse
    {
        $user = $request->authenticate();

        if ($user->hasRole('Admin') || $user->email_verified_at) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        $emailSent = $emailVerificationService->sendVerificationCode($user);

        $request->session()->put('auth.pending_verification_user_id', $user->id);
        $request->session()->put('auth.pending_remember', $request->boolean('remember'));

        return redirect()->route('auth.otp-verification')->with(
            'status',
            $emailSent
                ? 'We sent a 6-digit verification code to your email address.'
                : 'A verification code was already sent. Please check your email.'
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
