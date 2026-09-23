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
                            <label for="email">Email</label>
                            <div class="input-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 4a2 2 0 0 0-2 2v.4l9 5.625L19 6.4V6a2 2 0 0 0-2-2H3Z" />
                                    <path d="m19 8.525-9 5.625-9-5.625V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.525Z" />
                                </svg>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@sekolah.com"
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
                                >
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
    </body>
</html>
