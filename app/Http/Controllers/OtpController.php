<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OtpController extends Controller
{
    // Menampilkan halaman OTP
    public function showOtpForm($userId = null)
    {
        $user = null;
        if ($userId) {
            $user = User::find($userId);
        }
        return view('otp', compact('user'));
    }

    // Mengirim OTP untuk registrasi (inisialisasi)
    public function sendOtpForRegistration(Request $request)
    {
        // Ambil data registrasi dari session
        $registrationData = $request->session()->get('registration_data');
        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['error' => 'Data registrasi tidak ditemukan.']);
        }

        // Buat user sementara untuk OTP
        $user = new User();
        $user->fill($registrationData);

        // Generate OTP
        $otpCode = rand(100000, 999999);
        $user->otp_code = $otpCode;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);

        // Simpan user sementara
        $user->save();
        $request->session()->put('temp_user_id', $user->id);

        // Kirim email OTP
        try {
            Mail::to($user->email)->send(new OtpMail($otpCode));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim email OTP. Silakan coba lagi.']);
        }

        return redirect()->route('otp.verify', $user->id)->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // Mengirim OTP
    public function sendOtp(Request $request)
    {
        // Jika ada userId dari parameter (untuk verifikasi ulang)
        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
            if (!$user) {
                return redirect()->route('register')->withErrors(['error' => 'User tidak ditemukan.']);
            }
        } else {
            // Untuk registrasi baru, ambil dari session
            $registrationData = $request->session()->get('registration_data');
            if (!$registrationData) {
                return redirect()->route('register')->withErrors(['error' => 'Data registrasi tidak ditemukan.']);
            }

            // Buat user sementara untuk OTP
            $user = new User();
            $user->fill($registrationData);
        }

        // Generate OTP
        $otpCode = rand(100000, 999999);
        $user->otp_code = $otpCode;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);

        // Jika user belum ada di database, simpan sementara
        if (!$user->exists) {
            $user->save();
            $request->session()->put('temp_user_id', $user->id);
        } else {
            $user->save();
        }

        // Kirim email OTP
        try {
            Mail::to($user->email)->send(new OtpMail($otpCode));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim email OTP. Silakan coba lagi.']);
        }

        return redirect()->route('otp.verify', $user->id)->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = $request->input('user_id');
        $user = User::find($userId);

        if (!$user) {
            return back()->withErrors(['otp' => 'User tidak ditemukan.']);
        }

        // Cek apakah OTP valid dan belum expired
        if ($user->otp_code !== $request->otp || Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
        }

        // Cek apakah ini untuk registrasi baru
        $registrationData = $request->session()->get('registration_data');
        if ($registrationData) {
            // Update user dengan data registrasi lengkap
            $user->fill($registrationData);
            $request->session()->forget('registration_data');
        }

        // Verifikasi berhasil
        $user->email_verified_at = Carbon::now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Hapus data session
        $request->session()->forget('temp_user_id');

        // Login otomatis setelah verifikasi
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun Anda telah berhasil diverifikasi dan Anda telah login.');
    }
}
