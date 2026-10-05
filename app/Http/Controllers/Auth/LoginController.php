<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;

class LoginController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $path = $this->redirectPathForUser(Auth::user());

            if ($path !== null) {
                return redirect()->to($path);
            }

            Auth::logout();
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = $request->input('login');
        $loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $login,
            'password' => $request->input('password'),
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Email/Username atau password yang dimasukkan salah.']);
        }

        $request->session()->regenerate();

        // Pastikan role/permission yang dibaca selalu yang terbaru, bukan cache
        // dari user sebelumnya yang login di sesi/proses yang sama.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $path = $this->redirectPathForUser(Auth::user());

        if ($path === null) {
            Auth::logout();

            return back()->withErrors(['login' => 'Akun Anda belum memiliki role. Silakan hubungi Admin.']);
        }

        // Selalu arahkan ke panel sesuai role saat ini, jangan pakai URL "intended"
        // yang mungkin masih tersimpan dari percobaan akses panel lain sebelumnya.
        $request->session()->forget('url.intended');

        return redirect()->to($path);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('login');
    }

    protected function redirectPathForUser(User $user): ?string
    {
        if ($user->hasRole('super_admin') || $user->hasRole('Staf PKL')) {
            return '/admin';
        }

        if ($user->hasRole('Guru')) {
            return '/guru';
        }

        if ($user->hasRole('Siswa')) {
            return '/siswa';
        }

        return null;
    }
}
