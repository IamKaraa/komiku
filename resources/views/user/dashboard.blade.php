<!-- index.html -->
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>KOMIKU — Landing</title>
  @vite(['resources/css/dashboard.css'])
  @vite(['resources/css/header.css'])
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  @include('component.header')

  <main class="container">
    <section class="hero">
      <div class="hero-left">
        <h1>Find Your Story World in Komiku!</h1>
        <p>Find thousands of digital comics from talented creators in one place. Start reading or publish your story today.</p>
        <a class="cta" href="#">Get Started</a>
      </div>
      <img class="hero-img" src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png" alt="hero" />
    </section>

    <!-- Top Comic -->
    <section class="section">
      <div class="section-header">
        <h3>Top Comic</h3>
        <div class="chips">
          <div class="chip">Genre</div>
          <div class="chip">Action</div>
          <div class="chip">Romance</div>
          <div class="chip">Comedy</div>
        </div>
      </div>

      <div class="carousel">
        <div class="arrow left" data-target="row1"></div>
        <div class="arrow right" data-target="row1"></div>
        <div id="row1" class="row">
          <!-- repeated cards -->
          <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png" alt="cover"><div class="title">Judul Comic</div><div class="meta">Author • 12k</div></div>
          <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png" alt="cover"><div class="title">Judul Comic</div><div class="meta">Author • 8k</div></div>
          <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png" alt="cover"><div class="title">Judul Comic</div><div class="meta">Author • 5k</div></div>
          <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png" alt="cover"><div class="title">Judul Comic</div><div class="meta">Author • 2k</div></div>
          <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png" alt="cover"><div class="title">Judul Comic</div><div class="meta">Author • 1k</div></div>
        </div>
      </div>
    </section>

    <!-- New Released -->
    <section class="section">
      <div class="section-header"><h3>New Released</h3><div class="chips"><div class="chip">View All</div></div></div>
      <div class="row">
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
      </div>
    </section>

    <!-- For You -->
    <section class="section">
      <div class="section-header"><h3>For You</h3><div class="chips"><div class="chip">Based on your reading</div></div></div>
      <div class="row">
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
        <div class="card"><img src="/mnt/data/c09fc619-4e44-4e72-9ca5-da0c5297f8db.png"><div class="title">Judul Comic</div></div>
      </div>
    </section>

    <!-- Tags -->
    <section class="section">
      <p style="color:var(--muted);">All kinds of stories. All the feels. Just for you</p>
      <div class="tags">
        <div class="tag">Horror</div>
        <div class="tag">Drama</div>
        <div class="tag">Action</div>
        <div class="tag">Comedy</div>
        <div class="tag">Fantasy</div>
        <div class="tag">Romance</div>
        <div class="tag">Mystery</div>
        <div class="tag">Local</div>
      </div>
    </section>

    @include('component.footer')
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
</body>
</html>