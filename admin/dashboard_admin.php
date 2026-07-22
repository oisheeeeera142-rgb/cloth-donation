<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr($user_name, 0, 1));

$pending_cloth_reqs = $conn->query("SELECT COUNT(*) as t FROM Clothing_Request WHERE Cr_Status = 'Pending'")->fetch_assoc()['t'];
$pending_pickups = $conn->query("SELECT COUNT(*) as t FROM Pickup_Request WHERE Pr_Status = 'Pending'")->fetch_assoc()['t'];
$total_donors = $conn->query("SELECT COUNT(*) as t FROM Donor")->fetch_assoc()['t'];
$total_vols = $conn->query("SELECT COUNT(*) as t FROM Volunteer")->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">
    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
        <ul class="nav-links">
            <li><a href="dashboard_admin.php" class="active">Dashboard</a></li>
            <li><a href="manage_inventory.php">Manage Inventory</a></li>
            <li><a href="admin_requests.php">Review Requests</a></li>
            <li><a href="admin_pickups.php">Assign Pickups</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="profile_admin.php">Profile</a></li>
            <li><a href="../auth/logout.php" class="logout-link">Logout</a></li>
        </ul>
        <div class="sidebar-profile">
            <div class="profile-avatar" style="background: linear-gradient(135deg, #E76F51 0%, #c45a40 100%);"><?php echo $initials; ?></div>
            <div class="profile-info">
                <span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="profile-role">Admin</span>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="page-title">Admin Dashboard</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="welcome-banner" style="background: linear-gradient(135deg, #264653 0%, #1a3039 100%);">
                <h2>System Overview</h2>
                <p>Manage and monitor the entire ClotheCare platform.</p>
            </div>

            <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
                <div class="stat-card border-warning" onclick="window.location.href='admin_pickups.php'">
                    <div class="stat-value"><?php echo $pending_pickups; ?></div>
                    <div class="stat-label">Pending Pickups</div>
                </div>
                <div class="stat-card border-info" onclick="window.location.href='admin_requests.php'">
                    <div class="stat-value"><?php echo $pending_cloth_reqs; ?></div>
                    <div class="stat-label">Pending Clothing Reqs</div>
                </div>
                <div class="stat-card border-primary" onclick="window.location.href='manage_users.php'">
                    <div class="stat-value"><?php echo $total_donors; ?></div>
                    <div class="stat-label">Total Donors</div>
                </div>
                <div class="stat-card border-danger" onclick="window.location.href='manage_users.php'">
                    <div class="stat-value"><?php echo $total_vols; ?></div>
                    <div class="stat-label">Total Volunteers</div>
                </div>
            </div>

            <div class="quick-actions">
                <h3>Quick actions</h3>
                <div class="actions-grid">
                    <a href="admin_pickups.php" class="action-btn">Assign Pickups</a>
                    <a href="admin_requests.php" class="action-btn">Review Requests</a>
                    <a href="manage_inventory.php" class="action-btn">Inventory</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>