<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClotheCare - Connecting Hearts</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="landing-body">
    <h1 class="hero-title">ClotheCare</h1>
    <p class="hero-subtitle">Want to help others? Join our community today and start making a difference through clothing donations. Every piece connects a heart.</p>
    
    <div class="hero-buttons">
        <a href="login.php" class="btn-hero-primary">Sign In</a>
        <a href="register.php" class="btn-hero-secondary">Create Account</a>
    </div>
</body>
</html>