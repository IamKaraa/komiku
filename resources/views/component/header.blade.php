<header>
    <div class="logo">
        <a href="{{ route('home') }}"><img src="{{ asset('images/favicon.png') }}" alt="KOMIKU" class="logo-img" /></a>
        <span><strong>KOMIKU</strong></span>
    </div>

    <nav>
        <form action="">
            <input type="search" name="search" placeholder="apa yang kamu cari?">
        </form>
        <ul>
            <li><a href="#">Category</a></li>
            <li><a href="#">Ranking</a></li>
        </ul>
    </nav>

    @if (Auth::check())
        <div class="user-menu" id="userMenu">
            <button class="user-btn" id="userBtn" aria-haspopup="true" aria-expanded="false">
                @if(Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar User" id="profileAvatar" />
                @else
                    <div class="user-initials-circle">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                @endif
            </button>

            <div class="user-dropdown" id="userDropdown" role="menu" aria-hidden="true">
                <div class="user-info">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar User" id="profileAvatar" />
                    @else
                        <div class="user-initials-circle">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="user-meta">
                        <div class="user-name" id="userName">{{ Auth::user()->name }}</div>
                        <div class="user-email" id="userEmail">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <ul class="user-actions">
                    <li><a href="{{ route('profile') }}">Profile</a></li>
                    <li><a href="#" id="">History</a></li>
                    <li><a href="#" id="">Following</a></li>
                    @if (Auth::user()->role === 'admin')
                        <li><a href="#" id="adminLink">Panel Admin</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    @else
        <a class="btn-primary" href="{{ route('login') }}">Login</a>
    @endif
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute("href"));
                if (!target) return;

                const navbarHeight = document.querySelector("nav") ? document.querySelector("nav").offsetHeight : 0;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                const startPosition = window.pageYOffset;
                const startTime = performance.now();
                const duration = 800; // durasi animasi dalam ms

                function easeInOutQuad(t) {
                    return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
                }

                function animationScroll(currentTime) {
                    const timeElapsed = currentTime - startTime;
                    const progress = Math.min(timeElapsed / duration, 1);
                    const ease = easeInOutQuad(progress);
                    window.scrollTo(0, startPosition + (targetPosition - startPosition) * ease);

                    if (timeElapsed < duration) {
                        requestAnimationFrame(animationScroll);
                    }
                }

                requestAnimationFrame(animationScroll);
            });
        });
    });
</script>

