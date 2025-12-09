@extends('user.layout.user-app')

@section('title', 'KOMIKU - Following')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
<style>
    .comic-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 25px;
        justify-content: center;
        padding: 0 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .comic-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: left;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .comic-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .comic-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .comic-title {
        font-weight: bold;
        font-size: 16px;
        color: #333;
        margin-bottom: 5px;
        line-height: 1.3;
    }

    .comic-meta {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #666;
    }

    .genre {
        background: #f0f0f0;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 12px;
    }

    .rating {
        color: #ff9500;
        font-weight: bold;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #ddd;
    }
</style>
@endpush

@section('content')
<div class="w-full px-0 py-8">
    <!-- Header Section -->
    <section class="section mb-8 mx-4">
        <div class="text-center">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Following</h1>
            <p class="text-lg text-gray-600">Comics you're following</p>
        </div>
    </section>

    <!-- Following Comics Grid -->
    <section class="section mb-16 mx-4">
        @if(isset($followedComics) && $followedComics->count() > 0)
            <div class="comic-grid">
                @foreach($followedComics as $comic)
                <div class="comic-card">
                    <a href="{{ route('comic.detail', $comic->id) }}">
                        <img src="{{ $comic->thumbnail_path ? asset($comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
                             alt="{{ $comic->title }}" class="comic-img">
                        <div class="comic-info">
                            <p class="comic-title">{{ $comic->title }}</p>
                            <div class="comic-meta">
                                <span class="genre">{{ $comic->genres->first()->name ?? 'General' }}</span>
                                <span class="rating">⭐ {{ number_format($comic->average_rating, 1) }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-heart"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Followed Comics</h3>
                <p>You haven't followed any comics yet. Start exploring and follow some comics to see them here!</p>
                <a href="{{ route('comics.all') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    Browse Comics
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
