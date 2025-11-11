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

        // 2. Buat user langsung (skip OTP untuk simplicity)
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // default role
            'status' => 'active', // set default status
            'birth_date' => now()->toDateString(), // set default birth_date
            'email_verified_at' => now(), // set verified
        ]);

        // 3. Login user otomatis
        Auth::login($user);

        // 4. Redirect ke dashboard
        return redirect()->route('/user/dashboard');
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

            // Simpan objek user ke variabel lokal sebelum melakukan aksi logout
            $user = Auth::user();

            // KRUSIAL: Cek status verifikasi email
            if ($user->email_verified_at === null) {

                // Simpan ID user sebelum logout
                $userId = $user->id; // <<< AMBIL ID DI SINI

                Auth::logout(); // Logout user yang belum terverifikasi

                // Redirect ke halaman verifikasi menggunakan ID yang sudah disimpan
                return redirect()->route('otp.send', $userId)->with('error', 'Akun Anda belum terverifikasi. Silakan cek email Anda untuk kode OTP.');
            }

            // Jika sudah diverifikasi, lanjutkan
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
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