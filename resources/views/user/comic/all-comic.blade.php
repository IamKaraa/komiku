@extends('user.layout.user-app')

@section('title', 'KOMIKU - All Comics')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
@endpush

@section('content')
<div class="w-full px-0 py-8">
    <!-- Header Section -->
    <section class="section mb-8 mx-4">
        <div class="text-center">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">All Comics</h1>
            <p class="text-lg text-gray-600">Discover amazing stories from talented creators</p>
        </div>
    </section>

    <!-- Filter and Sort Section -->
    <section class="section mb-8 mx-4">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 w-full max-w-7xl mx-auto">
            <!-- Genre Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('comics.all') }}"
                   class="chip bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-medium transition duration-300">
                    All
                </a>
                @foreach($genres as $genre)
                    <a href="{{ route('comics.all', ['genre' => $genre->slug]) }}"
                       class="chip {{ request('genre') == $genre->slug ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 hover:bg-gray-200' }} px-4 py-2 rounded-full text-sm font-medium transition duration-300">
                        {{ $genre->name }}
                    </a>
                @endforeach
            </div>

            <!-- Sort Options -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Sort by:</span>
                <select id="sortSelect" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Comics Grid -->
    <section class="section mb-16 mx-4">
        @if($comics->count() > 0)
            <div class="row grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 w-full max-w-7xl mx-auto">
                @foreach($comics as $comic)
                <div class="card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <a href="{{ route('comic.detail', $comic->id) }}">
                        <img src="{{ $comic->thumbnail_path ? \Storage::disk('public')->url($comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
                             alt="{{ $comic->title }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h4 class="title font-semibold text-gray-900 mb-2 line-clamp-2">{{ $comic->title }}</h4>
                            <div class="meta text-sm text-gray-600">
                                <div>By {{ $comic->user->name }}</div>
                                <div class="flex items-center justify-between mt-1">
                                    <span>⭐ {{ number_format($comic->average_rating, 1) }}</span>
                                    <span>{{ number_format($comic->total_views) }} views</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $comics->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-book-open text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Comics Found</h3>
                <p class="text-gray-600">No comics available at the moment.</p>
            </div>
        @endif
    </section>
</div>

@push('scripts')
<script>
    // Sort functionality
    document.getElementById('sortSelect').addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('sort', this.value);
        window.location.href = url.toString();
    });
</script>
@endpush
@endsection
