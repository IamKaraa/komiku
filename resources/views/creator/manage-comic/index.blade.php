@extends('creator.layout.creator-app')

@section('title', 'Manage Comic')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Comic List</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Browse and manage your comics.</p>
    </div>
    <a href="{{ route('creator.comics.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
        <i data-lucide="plus" class="inline-block w-4 h-4 mr-2"></i>
        Add New Comic
    </a>
</div>

{{-- Comic Grid --}}
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
    @forelse ($comics ?? [] as $comic)
        {{-- Comic Card --}}
        <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-4 transition-all duration-300 hover:shadow-lg hover:scale-105">
            <img src="{{ $comic->thumbnail_path ? asset($comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}" alt="{{ $comic->title }}" class="w-full h-40 object-cover rounded-lg mb-3">
            <h4 class="font-bold text-lg text-gray-800 dark:text-white truncate mb-2">{{ $comic->title }}</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $comic->genres->pluck('name')->join(', ') }}</p>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-yellow-400"></i>
                    <span class="ml-1 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ number_format($comic->rating, 1) }}</span>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comic->chapters->count() }} chapters</span>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('creator.comics.show', $comic->id) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-center">
                    <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>Detail
                </a>
                <a href="{{ route('creator.comics.edit', $comic->id) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-center">
                    <i data-lucide="edit" class="w-4 h-4 inline mr-1"></i>Edit
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <i data-lucide="book-open" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">No Comics Yet</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't created any comics yet. Start by creating your first comic!</p>
            <a href="{{ route('creator.comics.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                Create Your First Comic
            </a>
        </div>
    @endforelse
</div>
</div>

{{-- Pagination (if needed) --}}
<div class="mt-8">
    {{ $comics->links() }}
</div>

@endsection