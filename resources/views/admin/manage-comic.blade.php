@extends('layouts.admin')

@section('title', 'Manage Comic')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Comic List</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Browse and manage your comics.</p>
    </div>
    <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
        <i data-lucide="plus" class="inline-block w-4 h-4 mr-2"></i>
        Add New Comic
    </a>
</div>

{{-- Comic Grid --}}
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
    @forelse ($comics ?? [] as $comic)
        {{-- Real Comic Card --}}
        <div class="bg-white rounded-2xl shadow-md p-4 transition-transform transform hover:scale-105 cursor-pointer">
            <img src="{{ $comic->thumbnail_path }}" alt="{{ $comic->title }}" class="w-full h-48 object-cover rounded-lg mb-3">
            <h4 class="font-bold text-lg text-[#1B263B] truncate">{{ $comic->title }}</h4>
            <p class="text-sm text-gray-500 mb-2">{{ $comic->genres->pluck('name')->join(', ') }}</p>
            <div class="flex items-center">
                <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-yellow-400"></i>
                <span class="ml-1 text-sm font-semibold text-gray-700">{{ number_format($comic->rating, 1) }}</span>
            </div>
        </div>
    @empty
        {{-- Dummy Comic Cards --}}
        @for ($i = 0; $i < 8; $i++)
            <div class="bg-white rounded-2xl shadow-md p-4 transition-transform transform hover:scale-105 cursor-pointer">
                <div class="w-full h-48 bg-gray-200 rounded-lg mb-3 flex items-center justify-center">
                    <i data-lucide="image-off" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h4 class="font-bold text-lg text-[#1B263B] truncate">Judul Comic {{ $i + 1 }}</h4>
                <p class="text-sm text-gray-500 mb-2">Genre, Action</p>
                <div class="flex items-center">
                    <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-yellow-400"></i>
                    <span class="ml-1 text-sm font-semibold text-gray-700">4.5</span>
                </div>
            </div>
        @endfor
    @endforelse
</div>
</div>

{{-- Pagination (if needed) --}}
<div class="mt-8">
    {{ $comics->links() }}
</div>

@endsection