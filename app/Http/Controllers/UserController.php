<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Genre;
use App\Models\Comic;
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
        return view('user.profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 3. Update Data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->bio = $request->input('bio');
        $user->birth_date = $request->input('birth_date');

        // 4. Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = 'storage/' . $avatarPath;
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

    public function dashboard()
    {
        $genres = Genre::all();

        try {
            // Fetch top comics by rating
            $topComics = Comic::published()
                ->with(['user', 'ratings'])
                ->orderByRaw('COALESCE((SELECT AVG(rating) FROM ratings WHERE ratings.comic_id = comic.id), 0) DESC')
                ->limit(10)
                ->get();

            // Fetch new released comics
            $newReleasedComics = Comic::published()
                ->with(['user', 'ratings'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Fetch random comics for "For You"
            $forYouComics = Comic::published()
                ->with(['user', 'ratings'])
                ->inRandomOrder()
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            // If there's an error with the queries, return empty collections
            $topComics = collect();
            $newReleasedComics = collect();
            $forYouComics = collect();
        }

        return view('user.dashboard', compact('genres', 'topComics', 'newReleasedComics', 'forYouComics'));
    }

    public function showProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }
        $initials = $this->getInitials($user->name);

        return view('user.profil', compact('user', 'initials'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function ranking()
    {
        return view('user.ranking');
    }

    public function following()
    {
        $user = Auth::user();
        $followedComics = $user->followedComics()->with(['genres', 'user'])->get();

        return view('user.user-menu.user-following', compact('followedComics'));
    }

    public function becomeCreator(Request $request)
    {
        $user = Auth::user();

        // Update role to creator
        $user->role = 'creator';
        $user->save();

        // Redirect to creator onboarding
        return redirect()->route('creator.onboarding')->with('success', 'Selamat! Anda sekarang menjadi creator.');
    }
}
