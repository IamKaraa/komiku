@extends('user.layout.user-app')

@section('title', 'KOMIKU - Reading History')

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
        position: relative;
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
        align-items: center;
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

    .last-read {
        font-size: 12px;
        color: #888;
        margin-top: 5px;
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

    .clear-history {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ff6b6b;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .comic-card:hover .clear-history {
        opacity: 1;
    }

    .clear-history:hover {
        background: #ff5252;
    }
</style>
@endpush

@section('content')
<div class="w-full px-0 py-8">
    <!-- Header Section -->
    <section class="section mb-8 mx-4">
        <div class="text-center">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Reading History</h1>
            <p class="text-lg text-gray-600">Continue where you left off</p>
        </div>
    </section>

    <!-- History Comics Grid -->
    <section class="section mb-16 mx-4">
        @if(isset($readingHistory) && $readingHistory->count() > 0)
            <div class="comic-grid">
                @foreach($readingHistory as $history)
                <div class="comic-card">
                    <a href="{{ route('comic.read', [$history->comic->id, $history->last_chapter_id ?? null]) }}">
                        <img src="{{ $history->comic->thumbnail_path ? asset($history->comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
                             alt="{{ $history->comic->title }}" class="comic-img">
                        <div class="comic-info">
                            <p class="comic-title">{{ $history->comic->title }}</p>
                            <div class="comic-meta">
                                <span class="genre">{{ $history->comic->genres->first()->name ?? 'General' }}</span>
                                <span class="rating">⭐ {{ number_format($history->comic->average_rating, 1) }}</span>
                            </div>
                            <div class="last-read">
                                Last read: {{ $history->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                    <button class="clear-history" onclick="removeFromHistory({{ $history->id }})" title="Remove from history">
                        ×
                    </button>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Reading History</h3>
                <p>You haven't read any comics yet. Start reading to see your history here!</p>
                <a href="{{ route('comics.all') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    Start Reading
                </a>
            </div>
        @endif
    </section>
</div>

@push('scripts')
<script>
    function removeFromHistory(historyId) {
        if (confirm('Remove this comic from your reading history?')) {
            // Add remove functionality here
            alert('Remove from history functionality to be implemented');
        }
    }
</script>
@endpush
@endsection
