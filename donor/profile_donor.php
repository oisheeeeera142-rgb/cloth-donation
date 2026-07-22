<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Donor') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$data = $conn->query("SELECT d.D_Name, de.D_Email, dp.D_Phone, da.* FROM Donor d LEFT JOIN Donor_Email de ON d.D_Id = de.D_Id LEFT JOIN Donor_Phone dp ON d.D_Id = dp.D_Id LEFT JOIN Donor_Address da ON d.DA_Id = da.DA_Id WHERE d.D_Id = $user_id")->fetch_assoc();
$initials = strtoupper(substr(explode(' ', $data['D_Name'])[0], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>My Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar"><div class="sidebar-brand">ClotheCare</div><ul class="nav-links"><li><a href="dashboard_donor.php">Dashboard</a></li><li><a href="donor_donations.php">My Donations</a></li><li><a href="donor_pickups.php">Pickup Requests</a></li><li><a href="profile_donor.php" class="active">Profile</a></li><li><a href="../auth/logout.php" class="logout-link">Logout</a></li></ul></div>
    <div class="main-content">
        <div class="topbar"><div class="page-title">Profile</div><a href="edit_profile_donor.php" class="btn-approve" style="text-decoration: none;">Edit Profile</a></div>
        <div class="content-wrapper"><div class="profile-card">
            <div class="profile-header-main"><div class="profile-avatar-large"><?php echo $initials; ?></div><div><h2><?php echo htmlspecialchars($data['D_Name']); ?></h2><div class="profile-status">Donor</div></div></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="info-group"><div class="info-text"><span class="info-label">Email</span><span class="info-value"><?php echo htmlspecialchars($data['D_Email'] ?? 'N/A'); ?></span></div></div>
                <div class="info-group"><div class="info-text"><span class="info-label">Phone</span><span class="info-value"><?php echo !empty($data['D_Phone']) ? "+880 ".htmlspecialchars($data['D_Phone']) : 'N/A'; ?></span></div></div>
                <div class="info-group" style="grid-column: span 2;"><div class="info-text"><span class="info-label">Address</span><span class="info-value"><?php echo htmlspecialchars(($data['D_House_No'] ?? '') . ", " . ($data['D_Street_No'] ?? '') . ", " . ($data['D_City'] ?? '') . ", " . ($data['D_District'] ?? '')); ?></span></div></div>
            </div>
        </div></div>
    </div>
</body>
</html>