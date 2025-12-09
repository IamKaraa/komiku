<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun - KOMIKU</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    @vite(['resources/css/otp.css']) {{-- Impor CSS baru --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="otp-box">
        <form class="form-glass" method="POST" 
              action="{{ route('otp.verify') }}">
            @csrf
            {{-- Tombol close dipindahkan ke sini dan diberi class close-btn --}}
            <span class="close-btn" onclick="window.history.back()">&times;</span> 
            <h2>Verifikasi Email Akun Anda</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first('code') }}</div>
            @endif

            <p class="info-text">Kami telah mengirimkan kode verifikasi 6 digit ke:</p>
            <p class="email-display">{{ $email }}</p>

            {{-- Input OTP (diubah menjadi div sederhana dengan label di atas) --}}
            <div class="input-group-otp"> 
                <label for="code" class="otp-label">Masukkan Kode OTP:</label>
                <input type="text" name="code" id="code" maxlength="6" required autofocus />
            </div>

            <button type="submit" class="otp-btn">Verifikasi & Aktifkan Akun</button>

            <p class="resend-text">
                Belum menerima kode?
                <a href="{{ route('otp.send') }}" id="resendLink">Kirim ulang kode</a>
                <span id="cooldownTimer" class="cooldown-timer"></span>
            </p>
        </form>
    </div>

    <script>
        const COOLDOWN_SECONDS = 30;
        const STORAGE_KEY = 'otp_resend_time';
        const resendLink = document.getElementById('resendLink');
        const cooldownTimer = document.getElementById('cooldownTimer');
        let timerInterval;

        function startCooldown() {
            const cooldownExpiry = Date.now() + COOLDOWN_SECONDS * 1000;
            localStorage.setItem(STORAGE_KEY, cooldownExpiry);
            
            resendLink.classList.add('resend-link-disabled');
            cooldownTimer.style.display = 'inline';
            
            clearInterval(timerInterval);
            timerInterval = setInterval(updateTimer, 1000);
            updateTimer(); 
        }

        function updateTimer() {
            const expiryTime = localStorage.getItem(STORAGE_KEY);
            if (!expiryTime) {
                stopCooldown();
                return;
            }

            const timeLeft = Math.floor((expiryTime - Date.now()) / 1000);

            if (timeLeft <= 0) {
                stopCooldown();
            } else {
                cooldownTimer.textContent = ` (Tunggu ${timeLeft}s)`;
            }
        }

        function stopCooldown() {
            clearInterval(timerInterval);
            localStorage.removeItem(STORAGE_KEY);
            resendLink.classList.remove('resend-link-disabled');
            cooldownTimer.style.display = 'none';
        }
        
        resendLink.addEventListener('click', function(e) {
            if (this.classList.contains('resend-link-disabled')) {
                e.preventDefault();
                return;
            }
            startCooldown();
        });

        const storedExpiry = localStorage.getItem(STORAGE_KEY);
        if (storedExpiry && storedExpiry > Date.now()) {
            startCooldown();
        } else {
            stopCooldown();
        }

    </script>
</body>

</html>
