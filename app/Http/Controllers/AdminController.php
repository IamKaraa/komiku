<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get total comics
        $totalComics = Comic::count();

        // Get active users (assuming status 'active')
        $activeUsers = User::where('status', 'active')->count();

        // Get pending users (assuming status 'pending' or something, but in seeder it's 'active', so maybe 0)
        $pendingUsers = User::where('status', 'pending')->count();

        // Get popular comic (assuming by created_at, take first)
        $popularComic = Comic::orderBy('created_at', 'desc')->first();

        // Get genre data for chart
        $genreData = DB::table('genres')
            ->join('comic_genre', 'genres.id', '=', 'comic_genre.genre_id')
            ->select('genres.name', DB::raw('count(*) as total'))
            ->groupBy('genres.name')
            ->pluck('total')
            ->toArray();

        $genreLabels = DB::table('genres')
            ->join('comic_genre', 'genres.id', '=', 'comic_genre.genre_id')
            ->select('genres.name')
            ->groupBy('genres.name')
            ->pluck('name')
            ->toArray();

        // Get daily views (assuming created_at for daily uploads)
        $dailyViews = Comic::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        return view('admin.dashboard', compact(
            'totalComics',
            'activeUsers',
            'pendingUsers',
            'popularComic',
            'genreData',
            'genreLabels',
            'dailyViews'
        ));
    }

    public function manageComics()
    {
        $comics = Comic::with(['genres', 'user', 'chapters'])->paginate(10);
        return view('admin.manage-comic.index', compact('comics'));
    }

    public function manageUsers()
    {
        $users = User::paginate(10);
        return view('admin.manage-user.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.manage-user.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,creator,admin',
            'status' => 'required|string|in:active,pending',
            'birth_date' => 'required|date',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'birth_date' => $request->birth_date,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.manage-user.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|in:user,creator,admin',
            'status' => 'required|string|in:active,pending',
            'birth_date' => 'required|date',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'role', 'status', 'birth_date']));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    // Comic CRUD
    public function createComic()
    {
        $genres = Genre::all();
        $creators = User::where('role', 'creator')->get();
        return view('admin.manage-comic.create', compact('genres', 'creators'));
    }

    public function storeComic(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $thumbnailPath = $request->file('thumbnail')->store('comics', 'public');

        $comic = Comic::create([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail_path' => $thumbnailPath,
            'slug' => Str::slug($request->title),
            'user_id' => $request->user_id,
        ]);

        $comic->genres()->attach($request->genres);

        return redirect()->route('admin.comics.index')->with('success', 'Comic created successfully.');
    }

    public function editComic($id)
    {
        $comic = Comic::with('genres')->findOrFail($id);
        $genres = Genre::all();
        return view('admin.manage-comic.edit', compact('comic', 'genres'));
    }

    public function updateComic(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $comic = Comic::findOrFail($id);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
        ];

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('comics', 'public');
            $data['thumbnail_path'] = $thumbnailPath;
        }

        $comic->update($data);
        $comic->genres()->sync($request->genres);

        return redirect()->route('admin.comics.index')->with('success', 'Comic updated successfully.');
    }

    public function deleteComic($id)
    {
        $comic = Comic::findOrFail($id);
        $comic->genres()->detach();
        $comic->delete();

        return redirect()->route('admin.comics.index')->with('success', 'Comic deleted successfully.');
    }

    public function approveComic($id)
    {
        $comic = Comic::findOrFail($id);
        $comic->update([
            'status' => 'published',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.comics.index')->with('success', 'Comic approved successfully.');
    }

    public function rejectComic($id)
    {
        $comic = Comic::findOrFail($id);
        $comic->update(['status' => 'rejected']);

        return redirect()->route('admin.comics.index')->with('success', 'Comic rejected successfully.');
    }

    // Genre CRUD
    public function manageGenres()
    {
        $genres = Genre::withCount('comics')->paginate(10);
        return view('admin.manage-genre.index', compact('genres'));
    }

    public function createGenre()
    {
        return view('admin.manage-genre.create');
    }

    public function storeGenre(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres',
        ]);

        Genre::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre created successfully.');
    }

    public function editGenre($id)
    {
        $genre = Genre::findOrFail($id);
        return view('admin.manage-genre.edit', compact('genre'));
    }

    public function updateGenre(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $id,
        ]);

        $genre = Genre::findOrFail($id);
        $genre->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre updated successfully.');
    }

    public function deleteGenre($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre deleted successfully.');
    }
}
