<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - KOMIKU</title>
    @vite(['resources/css/dashboard.css'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('component.header')

    <div class="dashboard-container">
        <div class="profile-section">
            <h1>Profil Pengguna</h1>
            <div class="profile-card">
                <div class="profile-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar User" />
                    @else
                        <div class="user-initials-circle">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                </div>
                <div class="profile-info">
                    <h2>{{ Auth::user()->name }}</h2>
                    <p>{{ Auth::user()->email }}</p>
                    <p>Role: {{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
    </div>

    @include('component.footer')
</body>
</html>
