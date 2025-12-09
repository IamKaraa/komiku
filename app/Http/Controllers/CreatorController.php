<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreatorController extends Controller
{
    public function dashboard()
    {
        // Get creator's comics
        $totalComics = Comic::where('user_id', Auth::id())->count();

        // Get total views (assuming views are tracked, for now use created comics count)
        $totalViews = Comic::where('user_id', Auth::id())->count();

        // Get total chapters
        $totalChapters = Comic::where('user_id', Auth::id())
            ->withCount('chapters')
            ->get()
            ->sum('chapters_count');

        // Get recent comics
        $recentComics = Comic::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('creator.dashboard', compact(
            'totalComics',
            'totalViews',
            'totalChapters',
            'recentComics'
        ));
    }

    public function manageComics()
    {
        $comics = Comic::where('user_id', Auth::id())->paginate(10);
        return view('creator.manage-comic.index', compact('comics'));
    }

    public function showComic($id)
    {
        $comic = Comic::where('user_id', Auth::id())->with(['genres', 'chapters', 'user'])->findOrFail($id);
        return view('creator.manage-comic.show', compact('comic'));
    }

    public function createComic()
    {
        $genres = Genre::all();
        return view('creator.manage-comic.create', compact('genres'));
    }

    public function storeComic(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'synopsis' => 'nullable|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
            'access_type' => 'required|in:0,1',
            'price' => 'nullable|integer|min:0|required_if:access_type,1',
            'status' => 'required|in:draft,ongoing,completed,hiatus',
        ]);

        $thumbnailPath = $request->file('thumbnail')->store('comics', 'public');

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail_path' => $thumbnailPath,
            'slug' => Str::slug($request->title),
            'user_id' => Auth::id(),
            'status' => $request->status,
            'is_paid' => $request->access_type == 1,
            'price' => $request->access_type == 1 ? $request->price : null,
        ];

        // Only add synopsis if the column exists
        if (Schema::hasColumn('comic', 'synopsis')) {
            $data['synopsis'] = $request->synopsis;
        }

        $comic = Comic::create($data);

        $comic->genres()->attach($request->genres);

        return redirect()->route('creator.comics.index')->with('success', 'Comic created successfully.');
    }

    public function editComic($id)
    {
        $comic = Comic::where('user_id', Auth::id())->with('genres')->findOrFail($id);
        $genres = Genre::all();
        return view('creator.manage-comic.edit', compact('comic', 'genres'));
    }

    public function updateComic(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'synopsis' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
            'access_type' => 'required|in:0,1',
            'price' => 'nullable|integer|min:0|required_if:access_type,1',
            'status' => 'required|in:draft,ongoing,completed,hiatus',
        ]);

        $comic = Comic::where('user_id', Auth::id())->findOrFail($id);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'status' => $request->status,
        ];

        // Only add synopsis if the column exists
        if (Schema::hasColumn('comic', 'synopsis')) {
            $data['synopsis'] = $request->synopsis;
        }

        // Handle access type and price
        if (Schema::hasColumn('comic', 'is_paid')) {
            $data['is_paid'] = $request->access_type == 1;
            if ($request->access_type == 1) {
                $data['price'] = $request->price;
            } else {
                $data['price'] = null;
            }
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('comics', 'public');
            $data['thumbnail_path'] = $thumbnailPath;
        }

        $comic->update($data);
        $comic->genres()->sync($request->genres);

        return redirect()->route('creator.comics.index')->with('success', 'Comic updated successfully.');
    }

    public function deleteComic($id)
    {
        $comic = Comic::where('user_id', Auth::id())->findOrFail($id);
        $comic->genres()->detach();
        $comic->delete();

        return redirect()->route('creator.comics.index')->with('success', 'Comic deleted successfully.');
    }

    public function createChapter($comicId)
    {
        $comic = Comic::where('user_id', Auth::id())->findOrFail($comicId);
        return view('creator.manage-comic.create-chapter', compact('comic'));
    }

    public function storeChapter(Request $request, $comicId)
    {
        $comic = Comic::where('user_id', Auth::id())->findOrFail($comicId);

        $request->validate([
            'title' => 'required|string|max:255',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the next order_no for the chapter
        $orderNo = $comic->chapters()->max('order_no') + 1 ?? 1;

        $chapter = Chapter::create([
            'comic_id' => $comic->id,
            'title' => $request->title,
            'order_no' => $orderNo,
            'published_at' => now(),
        ]);

        // Store images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('chapters', 'public');
                ChapterImage::create([
                    'chapter_id' => $chapter->id,
                    'image_path' => $path,
                    'order_no' => $index + 1,
                ]);
            }
        }

        return redirect()->route('creator.comics.show', $comic->id)->with('success', 'Chapter created successfully.');
    }

    public function editChapter($comicId, $chapterId)
    {
        $comic = Comic::where('user_id', Auth::id())->findOrFail($comicId);
        $chapter = Chapter::where('comic_id', $comic->id)->with('images')->findOrFail($chapterId);

        return view('creator.manage-comic.edit-chapter', compact('comic', 'chapter'));
    }

    public function updateChapter(Request $request, $comicId, $chapterId)
    {
        $comic = Comic::where('user_id', Auth::id())->findOrFail($comicId);
        $chapter = Chapter::where('comic_id', $comic->id)->findOrFail($chapterId);

        $request->validate([
            'title' => 'required|string|max:255',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'removed_images' => 'nullable|array',
            'removed_images.*' => 'integer|exists:chapter_images,id',
        ]);

        // Update chapter title
        $chapter->update([
            'title' => $request->title,
        ]);

        // Remove specified images
        if ($request->has('removed_images')) {
            $removedImages = ChapterImage::whereIn('id', $request->removed_images)
                ->where('chapter_id', $chapter->id)
                ->get();

            foreach ($removedImages as $image) {
                // Delete file from storage
                \Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Add new images
        if ($request->hasFile('new_images')) {
            $currentMaxOrder = $chapter->images()->max('order_no') ?? 0;

            foreach ($request->file('new_images') as $index => $image) {
                $path = $image->store('chapters', 'public');
                ChapterImage::create([
                    'chapter_id' => $chapter->id,
                    'image_path' => $path,
                    'order_no' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('creator.comics.show', $comic->id)->with('success', 'Chapter updated successfully.');
    }

    public function deleteChapter($comicId, $chapterId)
    {
        $comic = Comic::where('user_id', Auth::id())->findOrFail($comicId);
        $chapter = Chapter::where('comic_id', $comic->id)->findOrFail($chapterId);

        // Delete all associated images
        foreach ($chapter->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete the chapter
        $chapter->delete();

        return redirect()->route('creator.comics.show', $comic->id)->with('success', 'Chapter deleted successfully.');
    }
}
