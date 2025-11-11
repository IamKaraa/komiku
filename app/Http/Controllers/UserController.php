<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,student_online',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile-edit', compact('user')); // Kita akan buat view ini
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Data
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20', // Asumsi kolom phone ada
            'address' => 'nullable|string|max:255', // Asumsi kolom address ada
            'password' => 'nullable|string|min:8|confirmed', // Password baru opsional
        ]);

        // 2. Update Data
        $user->name = $request->input('nama'); // Asumsi input form menggunakan name="nama"
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');

        // 3. Update Password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function show()
    {
        return view('profile');
    }

    protected function getInitials(string $name): string
    {
        // Memecah nama menjadi kata-kata
        $words = explode(' ', trim($name));
        $initials = '';

        if (count($words) >= 2) {
            // Ambil huruf pertama dari dua kata pertama
            $initials = strtoupper($words[0][0] . $words[1][0]);
        } elseif (count($words) === 1 && !empty($words[0])) {
            // Jika hanya satu kata, ambil dua huruf pertamanya
            $initials = strtoupper(substr($words[0], 0, 2));
        }
        
        return $initials;
    }

    public function showProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }
        $initials = $this->getInitials($user->name);

        return view('profile', compact('user', 'initials'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
