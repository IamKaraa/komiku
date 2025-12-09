<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Genre;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $query = Comic::with(['genres', 'user'])->published();

        // Filter by genre if provided
        if ($request->has('genre') && $request->genre) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('created_at', 'desc'); // For now, using created_at as proxy
                break;
            case 'rating':
                $query->orderBy('created_at', 'desc'); // Would need to join with ratings
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $comics = $query->paginate(20);
        $genres = Genre::all();

        return view('user.comic.all-comic', compact('comics', 'genres'));
    }

    public function show($id)
    {
        $comic = Comic::with(['genres', 'chapters', 'user', 'ratings'])->findOrFail($id);

        // Get related comics by genre
        $relatedComics = Comic::whereHas('genres', function ($query) use ($comic) {
            $query->whereIn('genres.id', $comic->genres->pluck('id'));
        })
        ->where('id', '!=', $comic->id)
        ->published()
        ->limit(4)
        ->get();

        return view('user.comic.comic-detail', compact('comic', 'relatedComics'));
    }

    public function read($id, $chapter = null)
    {
        $comic = Comic::with(['chapters'])->findOrFail($id);

        // Check if comic is paid and user has purchased it
        if ($comic->isPaid()) {
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to read premium comics.');
            }

            $hasPurchased = \App\Models\Purchase::where('user_id', $user->id)
                ->where('comic_id', $comic->id)
                ->where('status', 'success')
                ->exists();

            if (!$hasPurchased) {
                return redirect()->route('comic.detail', $comic->id)
                    ->with('error', 'You need to purchase this comic to read it.');
            }
        }

        if (!$chapter) {
            $chapter = $comic->chapters->first();
        } else {
            $chapter = $comic->chapters->where('id', $chapter)->first();
        }

        if (!$chapter) {
            abort(404);
        }

        // Get chapter images
        $chapterImages = $chapter->images ?? [];

        // Get next and previous chapters
        $nextChapter = $comic->chapters->where('order_no', '>', $chapter->order_no)->first();
        $prevChapter = $comic->chapters->where('order_no', '<', $chapter->order_no)->last();

        return view('user.comic.comic-read', compact('comic', 'chapter', 'chapterImages', 'nextChapter', 'prevChapter'));
    }

    public function category(Request $request, $genreSlug = null)
    {
        $query = Comic::with(['genres', 'user'])->where('status', 'published');

        $selectedGenre = null;
        if ($genreSlug) {
            $selectedGenre = Genre::where('slug', $genreSlug)->firstOrFail();
            $query->whereHas('genres', function ($q) use ($selectedGenre) {
                $q->where('genres.id', $selectedGenre->id);
            });
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('created_at', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $comics = $query->paginate(20);
        $genres = Genre::all();

        return view('user.comic.category', compact('comics', 'genres', 'selectedGenre'));
    }

    public function follow(Request $request, $comicId)
    {
        $comic = Comic::findOrFail($comicId);
        $user = $request->user();

        if (!$user->followedComics()->where('comic_id', $comicId)->exists()) {
            $user->followedComics()->attach($comicId);
        }

        return response()->json(['success' => true, 'followed' => true]);
    }

    public function unfollow(Request $request, $comicId)
    {
        $comic = Comic::findOrFail($comicId);
        $user = $request->user();

        $user->followedComics()->detach($comicId);

        return response()->json(['success' => true, 'followed' => false]);
    }
}
