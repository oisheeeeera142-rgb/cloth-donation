<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'config/db.php';
if (isset($_SESSION['role'])) {
    $role = strtolower($_SESSION['role']);
    header("Location: {$role}/dashboard_{$role}.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ClotheCare - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="login-body">
    <?php if (isset($_SESSION['toast_error'])): ?>
        <div class="toast-container"><div class="toast toast-error"><?php echo $_SESSION['toast_error']; unset($_SESSION['toast_error']); ?></div></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['toast_success'])): ?>
        <div class="toast-container"><div class="toast toast-success"><?php echo $_SESSION['toast_success']; unset($_SESSION['toast_success']); ?></div></div>
    <?php endif; ?>
    <div class="login-header-text">
        <h1 style="font-size: 40px; margin-bottom: 16px;">Welcome Back</h1>
        <p>Sign in to continue to ClotheCare</p>
    </div>
    <div class="login-card">
        <div class="auth-tabs">
            <div class="auth-tab active">Sign In</div>
            <div class="auth-tab" onclick="window.location.href='register.php'">Register</div>
        </div>
        <form action="auth/login.php" method="POST">
            <div class="role-selector">
                <input type="radio" id="role-donor" name="role" value="Donor" required checked>
                <label for="role-donor" class="role-label">Donor</label>
                <input type="radio" id="role-volunteer" name="role" value="Volunteer">
                <label for="role-volunteer" class="role-label">Volunteer</label>
                <input type="radio" id="role-beneficiary" name="role" value="Beneficiary">
                <label for="role-beneficiary" class="role-label">Beneficiary</label>
                <input type="radio" id="role-admin" name="role" value="Admin">
                <label for="role-admin" class="role-label">Admin</label>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="login-password" class="form-control" placeholder="........" required>
                    <span class="password-toggle" id="toggle-login-icon" onclick="togglePassword('login-password', 'toggle-login-icon')">
                        <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn-primary">Sign In</button>
        </form>
    </div>
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<svg viewBox="0 0 24 24"><path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>';
            } else {
                input.type = "password";
                icon.innerHTML = '<svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>';
            }
        }
    </script>
</body>
</html>