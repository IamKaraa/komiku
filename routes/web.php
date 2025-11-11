<?php

use App\Http\Controllers\Auth\LoginController;
use App\Models\Comic;
use App\Models\User;
use App\Models\Genre;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes - Protected by auth middleware
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        // Data untuk Grafik Popularitas Genre
        $genrePopularity = Genre::withCount('comics')
            ->orderBy('comics_count', 'desc')
            ->take(5) // Ambil 5 genre terpopuler
            ->get();

        $genreLabels = $genrePopularity->pluck('name');
        $genreData = $genrePopularity->pluck('comics_count');

        // Data untuk Grafik View Komik Harian (contoh: 7 hari terakhir)
        $dailyViews = Comic::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'totalComics' => Comic::count(),
            'activeUsers' => User::where('status', 'active')->count(),
            'pendingUsers' => User::where('status', 'pending')->count(),
            'popularComic' => Comic::orderByDesc('views_count')->first() ?? new Comic(['title' => 'N/A', 'thumbnail_path' => '', 'views_count' => 0]),
            'genreLabels' => $genreLabels,
            'genreData' => $genreData,
            'dailyViews' => $dailyViews,
        ]);
    })->name('dashboard');

    Route::get('/users', function () {
        return view('admin.users.index', [
            'title' => 'Manage Users',
            'users' => User::latest()->paginate(10),
        ]);
    })->name('users.index');

    Route::get('/comics', function () {
        // Ambil data komik dari database. Jika kosong, view akan menampilkan data dummy.
        $comics = Comic::with('genres')->latest()->paginate(10);
        return view('admin.manage-comic', [
            'comics' => $comics,
        ]);
    })->name('comics.index');
    // Create User Routes
    Route::get('/users/create', function () {
        return view('admin.users.create', ['title' => 'Add New User']);
    })->name('users.create');

    Route::post('/users', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,creator,user',
            'status' => 'required|in:active,pending',
            'birth_date' => 'required|date',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
            'birth_date' => $validated['birth_date'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    })->name('users.store');

    // Edit User Routes
    Route::get('/users/{user}/edit', function (User $user) {
        return view('admin.users.edit', [
            'title' => 'Edit User: ' . $user->name,
            'user' => $user,
        ]);
    })->name('users.edit');

    Route::put('/users/{user}', function (Request $request, User $user) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,creator,user',
            'status' => 'required|in:active,pending',
            'birth_date' => 'required|date',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    })->name('users.update');

    // Delete User Route
    Route::delete('/users/{user}', function (User $user) {
        // Prevent deleting the main admin user
        if ($user->email === 'admin@example.com') {
            return back()->with('error', 'Cannot delete the main admin user.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    })->name('users.destroy');

    // Comic Detail Page
    Route::get('/comics/{comic:slug}', function (Comic $comic) {
        return view('admin.comics.show', [
            'title' => 'Comic Detail: ' . $comic->title,
            'comic' => $comic,
        ]);
    })->name('comics.show');
});
