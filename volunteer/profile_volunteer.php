<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Volunteer') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$data = $conn->query("SELECT v.V_Name, v.V_Email, v.V_Assigned_Area, vp.V_Phone FROM Volunteer v LEFT JOIN Volunteer_Phone vp ON v.V_Id = vp.V_Id WHERE v.V_Id = $user_id")->fetch_assoc();
$initials = strtoupper(substr(explode(' ', $data['V_Name'])[0], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>My Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar"><div class="sidebar-brand">ClotheCare</div><ul class="nav-links"><li><a href="dashboard_volunteer.php">Dashboard</a></li><li><a href="volunteer_pickups.php">My Pickups</a></li><li><a href="profile_volunteer.php" class="active">Profile</a></li><li><a href="../auth/logout.php" class="logout-link">Logout</a></li></ul></div>
    <div class="main-content">
        <div class="topbar"><div class="page-title">Profile</div><a href="edit_profile_volunteer.php" class="btn-approve" style="text-decoration: none;">Edit Profile</a></div>
        <div class="content-wrapper"><div class="profile-card">
            <div class="profile-header-main"><div class="profile-avatar-large"><?php echo $initials; ?></div><div><h2><?php echo htmlspecialchars($data['V_Name']); ?></h2><div class="profile-status">Volunteer</div></div></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="info-group"><div class="info-text"><span class="info-label">Email</span><span class="info-value"><?php echo htmlspecialchars($data['V_Email'] ?? 'N/A'); ?></span></div></div>
                <div class="info-group"><div class="info-text"><span class="info-label">Phone</span><span class="info-value"><?php echo !empty($data['V_Phone']) ? "+880 ".htmlspecialchars($data['V_Phone']) : 'N/A'; ?></span></div></div>
                <div class="info-group" style="grid-column: span 2;"><div class="info-text"><span class="info-label">Assigned Area</span><span class="info-value"><?php echo htmlspecialchars($data['V_Assigned_Area'] ?? 'Not Assigned'); ?></span></div></div>
            </div>
        </div></div>
    </div>
</body>
</html>