<header>
    <div class="logo">
        <a href="{{ route('home') }}"><span><strong>KOMIKU</strong></span></a>
    </div>

    <nav>
        <form action="">
            <input type="search" name="search" placeholder="apa yang kamu cari?">
        </form>
        <ul>
            <li><a href="{{ route('comics.category') }}">Category</a></li>
            <li><a href="{{ route('ranking') }}">Ranking</a></li>
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
                    <li><a href="{{ route('user.history') }}">History</a></li>
                    <li><a href="{{ route('user.following') }}">Following</a></li>
                    @if (Auth::user()->role === 'user')
                        <li>
                            <form action="{{ route('become.creator') }}" method="POST" id="becomeCreatorForm">
                                @csrf
                                <button type="submit" class="become-creator-link" onclick="return confirm('Apakah Anda yakin ingin menjadi creator?')">Jadi Creator</button>
                            </form>
                        </li>
                    @elseif (Auth::user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" id="adminLink">Panel Admin</a></li>
                    @elseif (Auth::user()->role === 'creator')
                        <li><a href="{{ route('creator.dashboard') }}" id="creatorLink">Panel Creator</a></li>
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
        // Handle smooth scrolling for anchor links
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

        // Handle user dropdown toggle
        const userBtn = document.getElementById('userBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isExpanded = userBtn.getAttribute('aria-expanded') === 'true';
                userBtn.setAttribute('aria-expanded', !isExpanded);
                userDropdown.setAttribute('aria-hidden', isExpanded);

                if (isExpanded) {
                    userDropdown.style.display = 'none';
                } else {
                    userDropdown.style.display = 'flex';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userBtn.setAttribute('aria-expanded', 'false');
                    userDropdown.setAttribute('aria-hidden', 'true');
                    userDropdown.style.display = 'none';
                }
            });

            // Close dropdown on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    userBtn.setAttribute('aria-expanded', 'false');
                    userDropdown.setAttribute('aria-hidden', 'true');
                    userDropdown.style.display = 'none';
                }
            });
        }
    });
</script>
