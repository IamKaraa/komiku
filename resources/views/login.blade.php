<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - KOMIKU</title>
  <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
  @vite(['resources/css/login.css'])
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="login-box">
    <form class="form-glass" id="loginForm" method="POST" action="{{ route('login') }}">
      @csrf
      <span class="close-btn" id="closeBtn">&times;</span>
      <h2>Login</h2>

      <div class="input-group">
        <label>
          <input type="email" name="email" placeholder="Email" required />
          <svg class="icon" fill="white" viewBox="0 0 24 24">
            <path
              d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
          </svg>
        </label>
      </div>

      <div class="input-group">
        <label>
          <input type="password" id="password" placeholder="Password" required name="password"
            autocomplete="new-password" />
          <svg class="password-toggle" id="togglePassword" fill="white" viewBox="0 0 24 24">
            <path
              d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
          </svg>
          <svg class="icon" fill="white" viewBox="0 0 24 24">
            <path
              d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
          </svg>
        </label>
      </div>

      <div class="form-options">
        <div class="checkbox-container">
          <input type="checkbox" name="remember" id="remember" />
          <label for="remember">Remember me</label>
        </div>
        <a href="#">Forgot Password?</a>
      </div>

      <button type="submit" class="login-btn">Login</button>

      <p class="register-text">
        Don't have an account? <a href="{{ url('/register') }}">Register</a>
      </p>
    </form>
  </div>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const closeBtn = document.getElementById("closeBtn");
    
    // Logika toggle password yang lebih bersih
    togglePassword.addEventListener("click", function () {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        this.classList.toggle("password-visible");
    });

    // Tombol kembali
    closeBtn.addEventListener("click", () => {
      window.history.back();
    });
    
    // Perbaikan: Validasi password harus dilakukan di backend.
    // Kode JavaScript yang sebelumnya sudah dihapus untuk menghindari duplikasi validasi.
  </script>
</body>

</html>
