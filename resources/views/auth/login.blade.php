<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - JALIN SMKN 13 Bandung</title>

        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                background: #f9fafb;
                color: #111827;
            }

            .wrapper {
                display: flex;
                min-height: 100vh;
            }

            .brand-panel {
                position: relative;
                width: 50%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow: hidden;
                padding: 3rem;
                background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
                color: #fff;
            }

            .brand-panel::before,
            .brand-panel::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.08);
            }

            .brand-panel::before {
                width: 18rem;
                height: 18rem;
                top: -6rem;
                left: -6rem;
            }

            .brand-panel::after {
                width: 24rem;
                height: 24rem;
                bottom: -8rem;
                right: -8rem;
            }

            .brand-logo {
                position: relative;
                z-index: 1;
                width: auto;
                filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.15));
            }

            .brand-text {
                position: relative;
                z-index: 1;
                max-width: 28rem;
            }

            .brand-text h1 {
                font-size: 1.875rem;
                font-weight: 700;
                line-height: 1.25;
                margin: 0 0 1rem;
            }

            .brand-text p {
                margin: 0;
                color: rgba(229, 231, 235, 0.9);
                line-height: 1.6;
            }

            .brand-footer {
                position: relative;
                z-index: 1;
                font-size: 0.8rem;
                color: rgba(229, 231, 235, 0.7);
            }

            .form-panel {
                width: 50%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 3rem 1.5rem;
            }

            .form-container {
                width: 100%;
                max-width: 22rem;
            }

            .mobile-logo {
                display: none;
                text-align: center;
                margin-bottom: 2rem;
            }

            .mobile-logo img {
                height: 4rem;
                width: auto;
            }

            .form-heading h2 {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0 0 0.25rem;
            }

            .form-heading p {
                margin: 0 0 2rem;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .alert-error {
                display: flex;
                align-items: flex-start;
                gap: 0.6rem;
                background: #fef2f2;
                border: 1px solid #fecaca;
                color: #b91c1c;
                border-radius: 0.5rem;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                margin-bottom: 1.5rem;
            }

            .alert-error svg {
                flex-shrink: 0;
                margin-top: 0.1rem;
                width: 1.1rem;
                height: 1.1rem;
            }

            .field {
                margin-bottom: 1.25rem;
            }

            .field label {
                display: block;
                margin-bottom: 0.4rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
            }

            .input-wrap {
                position: relative;
            }

            .input-wrap svg {
                position: absolute;
                left: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                width: 1.1rem;
                height: 1.1rem;
                color: #9ca3af;
            }

            .input-wrap input {
                width: 100%;
                padding: 0.65rem 0.75rem 0.65rem 2.5rem;
                font-size: 0.9rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                outline: none;
                transition: border-color 0.15s, box-shadow 0.15s;
                color: #111827;
            }

            .input-wrap input::placeholder {
                color: #9ca3af;
            }

            .input-wrap input:focus {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            }

            .input-wrap input.has-toggle {
                padding-right: 2.5rem;
            }

            .toggle-password {
                position: absolute;
                right: 0.5rem;
                top: 50%;
                transform: translateY(-50%);
                display: flex;
                align-items: center;
                justify-content: center;
                width: 1.75rem;
                height: 1.75rem;
                padding: 0;
                border: none;
                background: transparent;
                color: #9ca3af;
                cursor: pointer;
                border-radius: 0.375rem;
            }

            .toggle-password:hover {
                color: #4b5563;
                background: #f3f4f6;
            }

            .toggle-password svg {
                position: static;
                width: 1.1rem;
                height: 1.1rem;
                transform: none;
            }

            .toggle-password .icon-eye-off {
                display: none;
            }

            .toggle-password.is-visible .icon-eye {
                display: none;
            }

            .toggle-password.is-visible .icon-eye-off {
                display: block;
            }

            .remember-row {
                display: flex;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .remember-row input {
                width: 1rem;
                height: 1rem;
                margin-right: 0.5rem;
                accent-color: #2563eb;
            }

            .remember-row label {
                font-size: 0.875rem;
                color: #4b5563;
            }

            .btn-submit {
                width: 100%;
                padding: 0.7rem 1rem;
                font-size: 0.9rem;
                font-weight: 600;
                color: #fff;
                background: #2563eb;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                transition: background 0.15s;
            }

            .btn-submit:hover {
                background: #1d4ed8;
            }

            .btn-submit:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
            }

            .mobile-footer {
                display: none;
                text-align: center;
                margin-top: 2rem;
                font-size: 0.75rem;
                color: #9ca3af;
            }

            @media (max-width: 1023px) {
                .brand-panel {
                    display: none;
                }

                .form-panel {
                    width: 100%;
                }

                .mobile-logo,
                .mobile-footer {
                    display: block;
                }
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="brand-panel">
                <img src="{{ asset('images/logo-jalin.png') }}" alt="JALIN" class="brand-logo">

                <div class="brand-text">
                    <h1>Jurnal, Aktivitas, Lowongan, Industri &amp; Networking</h1>
                    <p>Platform terpadu untuk mengelola PKL siswa SMKN 13 Bandung &mdash; dari jurnal harian, lowongan, hingga penempatan industri.</p>
                </div>

                <p class="brand-footer">&copy; {{ now()->year }} SMKN 13 Bandung. All rights reserved.</p>
            </div>

            <div class="form-panel">
                <div class="form-container">
                    <div class="mobile-logo">
                        <img src="{{ asset('images/logo-jalin.png') }}" alt="JALIN SMKN 13 Bandung">
                    </div>

                    <div class="form-heading">
                        <h2>Selamat datang</h2>
                        <p>Masuk untuk melanjutkan ke JALIN SMKN 13 Bandung.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert-error">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="field">
                            <label for="login">Email atau Username</label>
                            <div class="input-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-7 7a7 7 0 0 1 14 0 1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Z" clip-rule="evenodd" />
                                </svg>
                                <input
                                    id="login"
                                    type="text"
                                    name="login"
                                    value="{{ old('login') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@gmail.com atau username"
                                >
                            </div>
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <div class="input-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                                </svg>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="has-toggle"
                                >
                                <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="icon-eye">
                                        <path d="M10 3.5c-4.08 0-7.44 2.61-8.74 6.25a.75.75 0 0 0 0 .5C2.56 13.89 5.92 16.5 10 16.5s7.44-2.61 8.74-6.25a.75.75 0 0 0 0-.5C17.44 6.11 14.08 3.5 10 3.5ZM10 14a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" />
                                        <path d="M10 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="icon-eye-off">
                                        <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06l-1.92-1.92c1.47-1.09 2.64-2.6 3.37-4.34a.75.75 0 0 0 0-.5C17.44 6.11 14.08 3.5 10 3.5c-1.47 0-2.84.35-4.05.97L3.28 2.22ZM7.52 6.46l1.4 1.4a2 2 0 0 1 2.22 2.22l1.4 1.4A4 4 0 0 0 7.52 6.46Z" clip-rule="evenodd" />
                                        <path d="M2.53 4.34 4.5 6.31C3.24 7.26 2.21 8.54 1.54 10a.75.75 0 0 0 0 .5c1.3 3.64 4.66 6.25 8.74 6.25 1.2 0 2.33-.22 3.37-.63l2.03 2.03a.75.75 0 1 0 1.06-1.06L3.59 3.28a.75.75 0 0 0-1.06 1.06Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="remember-row">
                            <input id="remember" type="checkbox" name="remember">
                            <label for="remember">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn-submit">Masuk</button>
                    </form>

                    <p class="mobile-footer">&copy; {{ now()->year }} JALIN &mdash; SMKN 13 Bandung</p>
                </div>
            </div>
        </div>

        <script>
            (function () {
                var toggleBtn = document.getElementById('togglePassword');
                var passwordInput = document.getElementById('password');

                if (!toggleBtn || !passwordInput) {
                    return;
                }

                toggleBtn.addEventListener('click', function () {
                    var isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';
                    toggleBtn.classList.toggle('is-visible', isHidden);
                    toggleBtn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
                });
            })();
        </script>
    </body>
</html>
