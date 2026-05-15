<?php require 'login_handler.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IntraSpots | Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body data-last-action="<?= htmlspecialchars($lastAction) ?>">

  <?php include 'header.html'; ?>

  <main class="login-page">
    <div class="auth-card">

      <span class="auth-eyebrow">IntraSpots</span>
      <h1 class="auth-title">Welcome Back</h1>
      <p class="auth-subtitle">Sign in to your account or create a new one to join the community.</p>
      <div class="gold-line"></div>

      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <!-- Tabs -->
      <div class="auth-tabs" role="tablist">
        <button
          class="auth-tab <?= (!$success && $lastAction !== 'register') ? 'active' : '' ?>"
          id="tab-login"
          role="tab"
          aria-controls="panel-login"
          aria-selected="<?= (!$success && $lastAction !== 'register') ? 'true' : 'false' ?>"
          onclick="switchTab('login')"
        >Sign In</button>
        <button
          class="auth-tab <?= (!$success && $lastAction === 'register') ? 'active' : '' ?>"
          id="tab-register"
          role="tab"
          aria-controls="panel-register"
          aria-selected="<?= (!$success && $lastAction === 'register') ? 'true' : 'false' ?>"
          onclick="switchTab('register')"
        >Create Account</button>
      </div>

      <!-- LOGIN PANEL -->
      <div
        class="auth-panel <?= (!$success && $lastAction !== 'register') ? 'active' : '' ?>"
        id="panel-login"
        role="tabpanel"
        aria-labelledby="tab-login"
      >
        <form method="POST" action="login.php" novalidate>
          <input type="hidden" name="action" value="login">

          <div class="form-group">
            <label for="identifier">Username or Email</label>
            <input
              type="text"
              id="identifier"
              name="identifier"
              placeholder="Enter your username or email"
              value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>"
              autocomplete="username"
              required
            >
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
              <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
              >
              <button type="button" class="toggle-pw" onclick="togglePw('password', this)" aria-label="Show password">👁</button>
            </div>
          </div>

          <div class="form-row-between">
            <a href="#" class="forgot-link">Forgot password?</a>
          </div>

          <button type="submit" class="btn-auth">Sign In</button>
        </form>

        <p class="auth-footer-note">
          Don't have an account?
          <a href="#" onclick="switchTab('register'); return false;">Create one</a>
        </p>
      </div>

      <!-- REGISTER PANEL -->
      <div
        class="auth-panel <?= (!$success && $lastAction === 'register') ? 'active' : '' ?>"
        id="panel-register"
        role="tabpanel"
        aria-labelledby="tab-register"
      >
        <form method="POST" action="login.php" novalidate>
          <input type="hidden" name="action" value="register">

          <div class="form-group">
            <label for="reg-username">Username</label>
            <input
              type="text"
              id="reg-username"
              name="username"
              placeholder="Choose a username"
              value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
              autocomplete="username"
              required
            >
          </div>

          <div class="form-group">
            <label for="reg-email">Email Address</label>
            <input
              type="email"
              id="reg-email"
              name="email"
              placeholder="your@email.com"
              value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
              autocomplete="email"
              required
            >
          </div>

          <div class="form-group">
            <label for="reg-password">Password</label>
            <div class="password-wrapper">
              <input
                type="password"
                id="reg-password"
                name="password"
                placeholder="At least 8 characters"
                autocomplete="new-password"
                required
              >
              <button type="button" class="toggle-pw" onclick="togglePw('reg-password', this)" aria-label="Show password">👁</button>
            </div>
          </div>

          <div class="form-group">
            <label for="reg-password2">Confirm Password</label>
            <div class="password-wrapper">
              <input
                type="password"
                id="reg-password2"
                name="password2"
                placeholder="Repeat your password"
                autocomplete="new-password"
                required
              >
              <button type="button" class="toggle-pw" onclick="togglePw('reg-password2', this)" aria-label="Show password">👁</button>
            </div>
          </div>

          <button type="submit" class="btn-auth">Create Account</button>
        </form>

        <p class="auth-footer-note">
          Already have an account?
          <a href="#" onclick="switchTab('login'); return false;">Sign in</a>
        </p>
      </div>

    </div><!-- /.auth-card -->
  </main>

  <footer class="page-footer">
    &copy; 2025 IntraSpots. All rights reserved.
  </footer>

  <script src="barScript.js"></script>
  <script src="login.js"></script>

</body>
</html>
