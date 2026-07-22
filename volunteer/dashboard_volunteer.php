<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Volunteer') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

$stmt1 = $conn->prepare("SELECT COUNT(*) as total FROM Pickup_Request WHERE V_Id = ?");
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$total_assigned = $stmt1->get_result()->fetch_assoc()['total'];

$stmt2 = $conn->prepare("SELECT COUNT(*) as completed FROM Pickup_Request WHERE V_Id = ? AND Pr_Status = 'Completed'");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$total_completed = $stmt2->get_result()->fetch_assoc()['completed'];

$stmt3 = $conn->prepare("SELECT COUNT(*) as pending FROM Pickup_Request WHERE V_Id = ? AND Pr_Status = 'Pending'");
$stmt3->bind_param("i", $user_id);
$stmt3->execute();
$total_pending = $stmt3->get_result()->fetch_assoc()['pending'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
        <ul class="nav-links">
            <li><a href="dashboard_volunteer.php" class="active">Dashboard</a></li>
            <li><a href="volunteer_pickups.php">My Pickups</a></li>
            <li><a href="profile_volunteer.php">Profile</a></li>
            <li><a href="../auth/logout.php" class="logout-link">Logout</a></li>
        </ul>
        <div class="sidebar-profile">
            <div class="profile-avatar"><?php echo $initials; ?></div>
            <div class="profile-info">
                <span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="profile-role">Volunteer</span>
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
                <p>Thank you for your service to the community.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card border-warning" onclick="window.location.href='volunteer_pickups.php'" style="cursor: pointer;">
                    <div class="stat-value"><?php echo $total_assigned; ?></div>
                    <div class="stat-label">Total Assigned</div>
                </div>
                <div class="stat-card border-primary" onclick="window.location.href='volunteer_pickups.php'" style="cursor: pointer;">
                    <div class="stat-value"><?php echo $total_completed; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card border-info" onclick="window.location.href='volunteer_pickups.php'" style="cursor: pointer;">
                    <div class="stat-value"><?php echo $total_pending; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>

            <div class="quick-actions">
                <h3>Quick actions</h3>
                <div class="actions-grid">
                    <a href="volunteer_pickups.php" class="action-btn">My Pickups</a>
                    <a href="profile_volunteer.php" class="action-btn">Profile</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>