@extends('user.layout.user-app')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
  @php
    $topComics = $topComics ?? collect();
    $newReleasedComics = $newReleasedComics ?? collect();
    $forYouComics = $forYouComics ?? collect();
  @endphp
  <main class="container">
    <section class="hero">
      <div class="hero-left">
        <h1>Find Your Story World in Komiku!</h1>
        <p>Find thousands of digital comics from talented creators in one place. Start reading or publish your story today.</p>
        @guest
        <a class="cta" href="{{ route('login') }}">Get Started</a>
        @endguest
      </div>
      <img class="hero-img" src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png" alt="hero" />
    </section>

    <!-- Top Comic -->
    <section class="section">
      <div class="section-header">
        <h3>Top Comic</h3>
        <div class="chips">
          <button class="chip genre-btn" data-genre="all">View All</button>
        </div>
      </div>

      <div class="carousel">
        <div class="arrow left" data-target="row1"></div>
        <div class="arrow right" data-target="row1"></div>
        <div id="row1" class="row">
          @forelse($topComics as $comic)
            <div class="card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
              <a href="{{ route('comic.detail', $comic->id) }}">
                <img src="{{ $comic->thumbnail_path ? asset($comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
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
          @empty
            <p class="no-comics-msg">Komik belum tersedia</p>
          @endforelse
        </div>
      </div>

    </section>

    <!-- New Released -->
    <section class="section">
      <div class="section-header">
        <h3>New Released</h3>
        <div class="chips">
          <div class="chip">View All</div>
        </div>
      </div>

      <div class="carousel">
        <div class="arrow left" data-target="row2"></div>
        <div class="arrow right" data-target="row2"></div>
        <div id="row2" class="row">
          @forelse($newReleasedComics as $comic)
            <div class="card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
              <a href="{{ route('comic.detail', $comic->id) }}">
                <img src="{{ $comic->thumbnail_path ? asset($comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
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
          @empty
            <p class="no-comics-msg">Komik belum tersedia</p>
          @endforelse
        </div>
      </div>
    </section>

    <!-- For You -->
    <section class="section">
      <div class="section-header">
        <h3>For You</h3>
        <div class="chips">
          <div class="chip">Based on your reading</div>
        </div>
      </div>

      <div class="carousel">
        <div class="arrow left" data-target="row3"></div>
        <div class="arrow right" data-target="row3"></div>
        <div id="row3" class="row">
          @forelse($forYouComics as $comic)
            <div class="card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
              <a href="{{ route('comic.detail', $comic->id) }}">
                <img src="{{ $comic->thumbnail_path ? asset($comic->thumbnail_path) : asset('images/comic-placeholder.jpg') }}"
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
          @empty
            <p class="no-comics-msg">Komik belum tersedia</p>
          @endforelse
        </div>
      </div>
    </section>

    <!-- Tags -->
    <section class="section">
      <p style="color:var(--muted);">All kinds of stories. All the feels. Just for you</p>
      <div class="tags">
        <button class="chip genre-btn" data-genre="all">All</button>
        @foreach($genres as $genre)
          <div class="tag">{{ $genre->name }}</div>
        @endforeach
      </div>
    </section>
  </main>

  <script>
    // small helper for arrow buttons to scroll the row
    document.querySelectorAll('.arrow').forEach(btn => {
      btn.addEventListener('click', ()=>{
        const target = document.getElementById(btn.dataset.target);
        if(!target) return;
        const dir = btn.classList.contains('left') ? -1 : 1;
        target.scrollBy({left: dir * 300, behavior:'smooth'});
      })
    })
  </script>
@endsection
