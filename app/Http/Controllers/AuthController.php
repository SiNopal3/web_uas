<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    /**
     * Tampilkan halaman register.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru (publik SELALU mendapatkan role = 'user').
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Role ditetapkan secara eksplisit sebagai User (Role id untuk User)
        $userRole = \App\Models\Role::firstOrCreate(
            ['name' => 'User'],
            ['description' => 'General User with standard access']
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $userRole->id,
            'status' => 'active',
        ]);
        $user->roles()->sync([$userRole->id]);

        // JANGAN Melakukan Auth::login($user) & JANGAN redirect ke Dashboard
        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login menggunakan akun Anda.');
    }

    /**
     * Proses autentikasi user (support username maupun email).
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('username');
        $password = $request->input('password');

        // Tentukan apakah input berupa email atau username/name
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $loginInput, 'password' => $password];
        } else {
            $authenticated = false;
            // Jika kolom username ada di tabel users, coba autentikasi dengan username
            if (Schema::hasColumn('users', 'username')) {
                if (Auth::attempt(['username' => $loginInput, 'password' => $password], $request->boolean('remember'))) {
                    $authenticated = true;
                }
            }
            // Jika belum berhasil, coba autentikasi dengan name
            if (!$authenticated && Auth::attempt(['name' => $loginInput, 'password' => $password], $request->boolean('remember'))) {
                $authenticated = true;
            }

            if ($authenticated) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }

            return back()->withErrors([
                'username' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
            ])->onlyInput('username');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
