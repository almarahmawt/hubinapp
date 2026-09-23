<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - JALIN SMKN 13 Bandung</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="antialiased bg-gray-50 text-gray-900">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
            <div class="w-full max-w-sm">
                <div class="mb-8 flex justify-center">
                    <img src="{{ asset('images/logo-jalin.png') }}" alt="JALIN SMKN 13 Bandung" class="h-16 w-auto">
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                        </div>

                        <div>
                            <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                            >
                        </div>

                        <div class="flex items-center">
                            <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600"
                        >
                            Login
                        </button>
                    </form>
                </div>

                <p class="mt-6 text-center text-xs text-gray-400">
                    &copy; {{ now()->year }} JALIN &mdash; SMKN 13 Bandung
                </p>
            </div>
        </div>
    </body>
</html>
