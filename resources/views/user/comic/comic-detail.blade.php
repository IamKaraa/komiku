@extends('user.layout.user-app')

@section('title', 'KOMIKU - ' . $comic->title)

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
@endpush

@section('content')
<main class="container">
    <!-- Banner Section -->
    <section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; min-height: 280px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('{{ $comic->thumbnail_path ? asset($comic->thumbnail_path) : asset("images/comic-placeholder.jpg") }}') center/cover; opacity: 0.3;"></div>
        <div style="position: relative; z-index: 1; text-align: center; color: #fff; background: rgba(0,0,0,0.6); padding: 20px; border-radius: 10px;">
            <h1 style="font-size: 3rem; font-weight: bold; margin-bottom: 1rem; color: #fff; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">{{ $comic->title }}</h1>
            <p style="font-size: 1.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">By {{ $comic->user->name }}</p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="section">
        <div class="w-full max-w-7xl mx-auto">
            <!-- Tags and Actions -->
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($comic->genres as $genre)
                    <a href="{{ route('comics.category', $genre->slug) }}" class="chip bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium transition duration-300">
                        {{ $genre->name }}
                    </a>
                @endforeach
                <span class="chip bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium">
                    EPS {{ $comic->chapters->count() }}
                </span>
                @if($comic->isPaid())
                    @php
                        $hasPurchased = auth()->check() && \App\Models\Purchase::where('user_id', auth()->id())
                            ->where('comic_id', $comic->id)
                            ->where('status', 'success')
                            ->exists();
                    @endphp
                    @if($hasPurchased)
                        <span class="chip bg-green-600 text-white px-4 py-2 rounded-full text-sm font-medium">
                            ✓ Purchased
                        </span>
                        <a href="{{ route('comic.read', $comic->id) }}" class="chip bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-blue-700 transition duration-300">
                            Read Now
                        </a>
                    @else
                        <a href="{{ route('comic.purchase', $comic->id) }}" class="chip bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-orange-700 transition duration-300">
                            🛒 Buy for Rp {{ number_format($comic->price, 0, ',', '.') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('comic.read', $comic->id) }}" class="chip bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-blue-700 transition duration-300">
                        Read Free
                    </a>
                @endif
                <button class="chip bg-red-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-red-700 transition duration-300">
                    Follow
                </button>
            </div>

            <!-- Comic Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Episodes List -->
                <div class="lg:col-span-2">
                    <h3 class="text-xl font-bold mb-4">Chapters</h3>
                    @if($comic->chapters->count() > 0)
                        <div class="space-y-3">
                            @foreach($comic->chapters->sortBy('order_no') as $chapter)
                                <a href="{{ route('comic.read', [$comic->id, $chapter->id]) }}" class="card bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 block">
                                    <div class="flex items-center p-4">
                                        <img src="{{ $chapter->images->first() ? asset($chapter->images->first()->image_path) : asset('images/chapter-placeholder.jpg') }}"
                                             alt="Chapter {{ $chapter->order_no }}" class="w-16 h-20 object-cover rounded mr-4">
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $chapter->title }}</div>
                                            <div class="text-sm text-gray-600">{{ $chapter->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="card bg-white rounded-lg shadow-md p-8 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-book-open text-4xl"></i>
                            </div>
                            <p class="text-gray-600">No chapters available yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="lg:col-span-1">
                    <div class="card bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-bold mb-4">Description</h3>
                        <p class="text-gray-700 leading-relaxed mb-4">{{ $comic->description ?? 'No description available.' }}</p>

                        <div class="border-t pt-4">
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>⭐ {{ number_format($comic->average_rating, 1) }} Rating</span>
                                <span>{{ number_format($comic->total_views) }} Views</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span>{{ $comic->chapters->count() }} Chapters</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            @if($relatedComics->count() > 0)
                <div class="mt-12">
                    <h3 class="text-xl font-bold mb-6">You May Also Like</h3>
                    <div class="row grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach($relatedComics as $relatedComic)
                            <div class="card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                                <a href="{{ route('comic.detail', $relatedComic->id) }}">
                                    <img src="{{ $relatedComic->thumbnail_path ? asset($relatedComic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
                                         alt="{{ $relatedComic->title }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h4 class="title font-semibold text-gray-900 mb-2 line-clamp-2">{{ $relatedComic->title }}</h4>
                                        <div class="meta text-sm text-gray-600">
                                            <div class="flex items-center justify-between">
                                                <span>⭐ {{ number_format($relatedComic->average_rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</main>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const followBtn = document.getElementById('follow-btn');
    if (!followBtn) return;

    followBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const comicId = {{ $comic->id }};
        const isFollowing = followBtn.textContent.trim() === 'Unfollow';

        const url = isFollowing ? `/comic/${comicId}/unfollow` : `/comic/${comicId}/follow`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.followed) {
                    followBtn.textContent = 'Unfollow';
                    followBtn.classList.remove('bg-red-600');
                    followBtn.classList.add('bg-gray-600');
                } else {
                    followBtn.textContent = 'Follow';
                    followBtn.classList.remove('bg-gray-600');
                    followBtn.classList.add('bg-red-600');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});
</script>
@endpush
@endsection
