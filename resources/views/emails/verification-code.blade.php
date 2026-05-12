{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Code</title>
</head>
<body style="margin:0; padding:24px; background-color:#f5f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">
        <tr>
            <td style="padding:32px 32px 16px; text-align:center;">
                <img src="{{ asset('assets/logo.png') }}" alt="Vogue Technics" style="max-width:180px; height:auto;">
            </td>
        </tr>
        <tr>
            <td style="padding:0 32px 32px;">
                <h2 style="margin:0 0 12px; font-size:24px; color:#111827;">Verify Your Email</h2>
                <p style="margin:0 0 16px; font-size:15px; line-height:1.6;">
                    Hello {{ $user->name }}, use the verification code below to continue signing in to Vogue Portal.
                </p>
                <div style="margin:24px 0; padding:18px; text-align:center; background:#eef2ff; border-radius:10px; border:1px dashed #4f46e5;">
                    <span style="display:block; font-size:32px; letter-spacing:10px; font-weight:700; color:#111827;">{{ $code }}</span>
                </div>
                <p style="margin:0 0 8px; font-size:14px; line-height:1.6;">
                    This code will expire in 10 minutes.
                </p>
                <p style="margin:0; font-size:14px; line-height:1.6; color:#6b7280;">
                    If you did not try to sign in, you can safely ignore this email.
                </p>
            </td>
        </tr>
    </table>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>

<body style="margin:0; padding:0; background:#f1f5f9; font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 15px;">
        <tr>
            <td align="center">

                <!-- Card -->
                <table width="100%" cellpadding="0" cellspacing="0"
                       style="max-width:600px; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#4f46e5,#6366f1); padding:30px; text-align:center;">
                            <img src="{{ asset('assets/logo.png') }}"
                                 alt="Vogue Technics"
                                 style="max-width:160px;">
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:35px 30px; color:#1f2937;">

                            <h2 style="margin:0 0 10px; font-size:24px; font-weight:700;">
                                Verify Your Email
                            </h2>

                            <p style="margin:0 0 20px; font-size:15px; color:#4b5563; line-height:1.6;">
                                Hi <strong>{{ $user->name }}</strong>,  
                                use the verification code below to securely access your account.
                            </p>

                            <!-- Code Box -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center"
                                        style="background:#f8fafc; border:2px dashed #6366f1; border-radius:12px; padding:20px;">

                                        <span style="font-size:34px; font-weight:700; letter-spacing:12px; color:#111827;">
                                            {{ $code }}
                                        </span>

                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 10px; font-size:14px; color:#6b7280;">
                                ⏳ This code will expire in <strong>10 minutes</strong>.
                            </p>

                            <p style="margin:0; font-size:14px; color:#9ca3af;">
                                If you didn’t request this, you can safely ignore this email.
                            </p>

                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="height:1px; background:#e5e7eb;"></td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 30px; text-align:center; font-size:12px; color:#9ca3af;">
                            © {{ date('Y') }} Vogue Technics. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
