<!doctype html>

<html lang="en" class=" layout-wide  customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>OTP Verification | Vogue Technics</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/vt_favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <div class="authentication-wrapper authentication-basic px-6">
      <div class="authentication-inner py-6">
        <div class="card">
          <div class="card-body">
            <div class="app-brand justify-content-center mb-6">
              <a href="{{ route('login') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                  <img src="{{ asset('assets/logo.png') }}" alt="logo" width="200">
                </span>
              </a>
            </div>

            <h4 class="mb-1">OTP Verification</h4>
            <p class="text-start mb-6">
              We sent a verification code to your email address. Enter the code from the email in the field below.
              <span class="fw-medium d-block mt-1 text-heading">{{ $maskedEmail }}</span>
            </p>

            @if (session('status'))
              <div class="alert alert-success" role="alert">{{ session('status') }}</div>
            @endif

            <p class="mb-0">Type your 6 digit security code</p>
            <form id="twoStepsForm" action="{{ route('auth.otp-verification.verify') }}" method="POST">
              @csrf
              <div class="mb-6 form-control-validation">
                <div class="auth-input-wrapper d-flex align-items-center justify-content-between numeral-mask-wrapper">
                  <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" autofocus />
                  <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
                  <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
                  <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
                  <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
                  <input type="tel" class="form-control auth-input h-px-50 text-center numeral-mask mx-sm-1 my-2" maxlength="1" />
                </div>
                <input type="hidden" name="otp" />
              </div>

              @error('otp')
                <div class="text-danger mb-4">{{ $message }}</div>
              @enderror

              <button class="btn btn-primary d-grid w-100 mb-6">Verify my account</button>
            </form>

            <div class="text-center">
              Didn't get the code?
              <form action="{{ route('auth.otp-verification.resend') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link p-0 align-baseline">Resend</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleave-zen/cleave-zen.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
    <script src="{{ asset('assets/js/pages-auth-two-steps.js') }}"></script>
  </body>
</html>
