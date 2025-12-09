<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    // Menampilkan halaman registrasi
    public function showRegisterForm()
    {
        return view('/register');
    }

    // Memproses data registrasi
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Memastikan email belum ada
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Simpan data registrasi ke session
        $registrationData = [
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active',
            'birth_date' => now()->toDateString(),
        ];

        $request->session()->put('registration_data', $registrationData);

        // 3. Redirect ke kirim OTP
        return redirect()->route('otp.send.init');
    }

    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('/login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tangkap nilai checkbox remember me
        $remember = $request->filled('remember');

        // Coba untuk login
        if (Auth::attempt($credentials, $remember)) {

            // Simpan objek user ke variabel lokal
            $user = Auth::user();

            // Jika sudah diverifikasi, lanjutkan (skip OTP check untuk sementara)
            $request->session()->regenerate();

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'creator') {
                return redirect()->route('creator.dashboard');
            } else {
                return redirect()->intended('dashboard');
            }
        }



        // Jika login gagal
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials, $request->remember)) {
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal
        return redirect()->back()->with('error', 'Email atau password salah!');
    }


    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('status', 'Anda telah berhasil logout.');
    }
}