@extends('user.layout.user-app')

@section('title', 'KOMIKU - ' . $comic->title . ' - ' . $chapter->title)

@push('styles')
<style>
    .reader-container {
        width: 100%;
        max-width: 800px;
        margin: 20px auto;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .comic-image {
        width: 100%;
        margin: 10px 0;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 0 20px;
    }
    .nav-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .nav-btn:hover {
        background: #0056b3;
    }
    .nav-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }
    .chapter-info {
        text-align: center;
        margin-bottom: 20px;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .chapter-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    .chapter-meta {
        color: #666;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="w-full px-0 py-8">
    <!-- Chapter Info -->
    <section class="section mb-8 mx-4">
        <div class="chapter-info w-full max-w-4xl mx-auto">
            <h1 class="chapter-title">{{ $comic->title }}</h1>
            <div class="chapter-meta">
                <span>{{ $chapter->title }}</span> |
                <span>Chapter {{ $chapter->order_no }}</span> |
                <span>{{ $chapter->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </section>

    <!-- Reader Container -->
    <section class="section mb-16 mx-4">
        <div class="reader-container">
            @if($chapterImages && $chapterImages->count() > 0)
                @foreach($chapterImages->sortBy('order_no') as $image)
                <img src="{{ \Storage::disk('public')->url($image->image_path) }}" alt="Page {{ $image->order_no }}" class="comic-image">
                @endforeach
            @else
                <div class="text-center py-16">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-image text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Images Available</h3>
                    <p class="text-gray-600">This chapter doesn't have any images yet.</p>
                </div>
            @endif
        </div>

        <!-- Navigation -->
        <div class="navigation w-full max-w-4xl mx-auto">
            @if($prevChapter)
                <a href="{{ route('comic.read', [$comic->id, $prevChapter->id]) }}" class="nav-btn">
                    ← Previous Chapter
                </a>
            @else
                <span class="nav-btn" style="visibility: hidden;">← Previous Chapter</span>
            @endif

            <a href="{{ route('comic.detail', $comic->id) }}" class="nav-btn" style="background: #6c757d;">
                Back to Comic
            </a>

            @if($nextChapter)
                <a href="{{ route('comic.read', [$comic->id, $nextChapter->id]) }}" class="nav-btn">
                    Next Chapter →
                </a>
            @else
                <span class="nav-btn" style="visibility: hidden;">Next Chapter →</span>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script>
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        @if($prevChapter)
        if (e.key === 'ArrowLeft') {
            window.location.href = '{{ route("comic.read", [$comic->id, $prevChapter->id]) }}';
        }
        @endif

        @if($nextChapter)
        if (e.key === 'ArrowRight') {
            window.location.href = '{{ route("comic.read", [$comic->id, $nextChapter->id]) }}';
        }
        @endif

        if (e.key === 'Escape') {
            window.location.href = '{{ route("comic.detail", $comic->id) }}';
        }
    });
</script>
@endpush
@endsection
