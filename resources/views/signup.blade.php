<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - KOMIKU</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <!-- Logo -->
  <div class="logo">KOMIKU</div>

  <!-- Form container -->
  <div class="signup-container">
    <h2>Sign up</h2>

    <form action="#" method="POST">
      @csrf
      <div class="input-group">
        <input type="text" placeholder="Email or Phone Number" required />
        <span class="icon">✉️</span>
      </div>

      <div class="input-group">
        <input type="password" placeholder="Create New Password" required />
        <span class="icon">🔒</span>
      </div>

      <button type="submit" class="btn">Sign Up</button>
    </form>

    <p class="signin-text">
      Already have an account?
      <a href="#">Sign In</a>
    </p>
  </div>
</body>
</html>
