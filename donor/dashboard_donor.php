<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Donor') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

$stmt1 = $conn->prepare("SELECT COUNT(*) as total FROM Clothing_Item WHERE D_Id = ?");
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$total_donations = $stmt1->get_result()->fetch_assoc()['total'];

$stmt2 = $conn->prepare("SELECT COUNT(*) as total FROM Pickup_Request WHERE D_Id = ? AND Pr_Status = 'Pending'");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$pending_pickups = $stmt2->get_result()->fetch_assoc()['total'];

$stmt3 = $conn->prepare("SELECT COUNT(*) as total FROM Pickup_Request WHERE D_Id = ? AND Pr_Status = 'Completed'");
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$completed_pickups = $stmt3->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
        <ul class="nav-links">
            <li><a href="dashboard_donor.php" class="active">Dashboard</a></li>
            <li><a href="donor_donations.php">My Donations</a></li>
            <li><a href="donor_pickups.php">Pickup Requests</a></li>
            <li><a href="profile_donor.php">Profile</a></li>
            <li><a href="../auth/logout.php" class="logout-link">Logout</a></li>
        </ul>
        <div class="sidebar-profile">
            <div class="profile-avatar"><?php echo $initials; ?></div>
            <div class="profile-info">
                <span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="profile-role">Donor</span>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="page-title">Dashboard</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="welcome-banner">
                <h2>Good morning, <?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?>!</h2>
                <p>Your contributions are making a difference.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card border-info" onclick="window.location.href='donor_donations.php'" style="cursor: pointer;">
                    <div class="stat-value"><?php echo $total_donations; ?></div>
                    <div class="stat-label">Total Donations</div>
                </div>
                <div class="stat-card border-warning" onclick="window.location.href='donor_pickups.php'" style="cursor: pointer;">
                    <div class="stat-value"><?php echo $pending_pickups; ?></div>
                    <div class="stat-label">Pending Pickups</div>
                </div>
                <div class="stat-card border-primary" onclick="window.location.href='donor_pickups.php'" style="cursor: pointer;">
                    <div class="stat-value"><?php echo $completed_pickups; ?></div>
                    <div class="stat-label">Completed Pickups</div>
                </div>
            </div>

            <div class="quick-actions">
                <h3>Quick actions</h3>
                <div class="actions-grid">
                    <a href="donor_donate.php" class="action-btn">Donate Clothes</a>
                    <a href="donor_request_pickup.php" class="action-btn">Request Pickup</a>
                    <a href="profile_donor.php" class="action-btn">Profile</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>