<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {

        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'error' => ['Username tidak terdaftar.'],
                ]);
            }

            if (!in_array($user->role, ['admin', 'dokter', 'resepsionis', 'pasien'])) {
                throw ValidationException::withMessages([
                    'error' => ['Akun ini tidak memiliki akses.'],
                ]);
            }

            if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                throw ValidationException::withMessages([
                    'error' => ['Password salah.'],
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan pada server. Silakan coba lagi.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
