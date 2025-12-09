<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verifikasi OTP - KOMIKU</title>
  <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
  @vite(['resources/css/otp.css'])
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="otp-box">
    <form class="form-glass" method="POST" action="/otp/verify">
      @csrf
      <input type="hidden" name="user_id" value="{{ $user ? $user->id : old('user_id') }}">
      <span class="close-btn" onclick="window.history.back()">&times;</span>
      <h2>Verifikasi Email</h2>

      @if ($errors->any())
          <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; padding: 10px; margin-bottom: 15px;">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      @if (session('success'))
          <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; padding: 10px; margin-bottom: 15px;">
              {{ session('success') }}
          </div>
      @endif

      <p style="text-align: center; margin-bottom: 20px;">
        Masukkan kode OTP yang telah dikirim ke email Anda:
        @if($user)
          <strong>{{ $user->email }}</strong>
        @endif
      </p>

      <div class="otp-input-group">
        <input type="text" name="otp" id="otp" placeholder="Masukkan 6 digit kode OTP" maxlength="6" required />
      </div>

      <button type="submit" class="otp-btn">Verifikasi</button>

      <p class="resend-text">
        Tidak menerima kode? <a href="#" id="resendOtp">Kirim ulang</a>
      </p>
    </form>
  </div>

  <script>
    // Auto-focus dan validasi input OTP
    const otpInput = document.getElementById('otp');

    otpInput.addEventListener('input', function(e) {
      // Hanya izinkan angka
      this.value = this.value.replace(/[^0-9]/g, '');

      // Auto-submit jika 6 digit
      if (this.value.length === 6) {
        this.form.submit();
      }
    });

    // Resend OTP
    document.getElementById('resendOtp').addEventListener('click', function(e) {
      e.preventDefault();
      if (confirm('Kirim ulang kode OTP?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("otp.send") }}';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const userId = document.createElement('input');
        userId.type = 'hidden';
        userId.name = 'user_id';
        userId.value = '{{ $user ? $user->id : "" }}';

        form.appendChild(csrf);
        form.appendChild(userId);
        document.body.appendChild(form);
        form.submit();
      }
    });
  </script>
</body>

</html>
