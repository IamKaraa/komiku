@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col md:flex-row gap-8">
        <div class="md:w-1/4 flex-shrink-0">
            <img src="{{ $comic->thumbnail_path ?: asset('images/default-thumbnail.png') }}" alt="{{ $comic->title }}" class="w-full h-auto object-cover rounded-md shadow-md">
        </div>
        <div class="md:w-3/4">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">{{ $comic->title }}</h2>
            
            <div class="flex flex-wrap gap-2 mb-4">
                @forelse ($comic->genres as $genre)
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                        {{ $genre->name }}
                    </span>
                @empty
                    <span class="text-gray-500 dark:text-gray-400">No genres assigned.</span>
                @endforelse
            </div>

            <p class="text-gray-600 dark:text-gray-300 mb-4">
                {{ $comic->description }}
            </p>

            <a href="#" class="text-blue-500 hover:underline">&larr; Back to Comics List</a>
        </div>
    </div>
</div>
@endsection
