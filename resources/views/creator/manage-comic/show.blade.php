@extends('creator.layout.creator-app')

@section('title', 'Comic Detail - ' . $comic->title)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Comic Detail</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comic->title }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('creator.comics.edit', $comic->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
            <i data-lucide="edit" class="inline-block w-4 h-4 mr-2"></i>
            Edit Comic
        </a>
        <a href="{{ route('creator.comics.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
            <i data-lucide="arrow-left" class="inline-block w-4 h-4 mr-2"></i>
            Back
        </a>
    </div>
</div>

{{-- Comic Banner --}}
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg mb-6">
    <div class="relative h-64 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <img src="{{ $comic->thumbnail_path ? asset($comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
             alt="{{ $comic->title }}"
             class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 flex flex-col justify-center items-center text-white">
            <h1 class="text-4xl font-bold mb-2">{{ $comic->title }}</h1>
            <p class="text-lg">By {{ $comic->user->name }}</p>
        </div>
    </div>
</div>

{{-- Comic Info --}}
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg mb-6">
    {{-- Tags --}}
    <div class="mb-6">
        @foreach($comic->genres as $genre)
            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium mr-2 mb-2">
                {{ $genre->name }}
            </span>
        @endforeach
        <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm font-medium mr-2 mb-2">
            {{ $comic->status }}
        </span>
        <span class="inline-block {{ $comic->is_paid ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }} px-3 py-1 rounded-full text-sm font-medium">
            {{ $comic->is_paid ? 'Paid' : 'Free' }}
        </span>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl text-center">
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($comic->average_rating, 1) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Rating</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl text-center">
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($comic->total_views) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Views</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl text-center">
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $comic->chapters->count() }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Chapters</div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl text-center">
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $comic->total_followers }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Followers</div>
        </div>
    </div>

    {{-- Description --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chapters --}}
        <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Chapters</h3>
                <a href="{{ route('creator.comics.createChapter', $comic->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
                    <i data-lucide="plus" class="inline-block w-4 h-4 mr-2"></i>
                    Tambah Chapter
                </a>
            </div>
            @forelse($comic->chapters->sortBy('order_no') as $chapter)
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl mb-4 flex gap-4">
                    <img src="{{ $chapter->images->first() ? asset($chapter->images->first()->image_path) : asset('images/chapter-placeholder.jpg') }}"
                         alt="{{ $chapter->title }}"
                         class="w-16 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $chapter->title }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $chapter->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('creator.comics.editChapter', [$comic->id, $chapter->id]) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-105"
                           title="Edit Chapter">
                            <i data-lucide="edit" class="w-4 h-4"></i>Edit
                        </a>
                        <form action="{{ route('creator.comics.deleteChapter', [$comic->id, $chapter->id]) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this chapter?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-105"
                                    title="Delete Chapter">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i data-lucide="book-open" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">No chapters yet.</p>
                </div>
            @endforelse
        </div>

        {{-- Description Box --}}
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Description</h3>
            <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $comic->description ?? 'No description available.' }}</p>

            @if($comic->synopsis)
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Synopsis</h4>
                <p class="text-gray-600 dark:text-gray-400">{{ $comic->synopsis }}</p>
            @endif
        </div>
    </div>
</div>

@endsection
