<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Beneficiary') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$data = $conn->query("SELECT b.B_Name, bp.B_Phone, ba.* FROM Beneficiary b LEFT JOIN Beneficiary_Phone bp ON b.B_Id = bp.B_Id LEFT JOIN Beneficiary_Address ba ON b.BA_Id = ba.BA_Id WHERE b.B_Id = $user_id")->fetch_assoc();
$initials = strtoupper(substr(explode(' ', $data['B_Name'])[0], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>My Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar"><div class="sidebar-brand">ClotheCare</div><ul class="nav-links"><li><a href="dashboard_beneficiary.php">Dashboard</a></li><li><a href="beneficiary_requests.php">My Requests</a></li><li><a href="beneficiary_request_cloth.php">Request Clothes</a></li><li><a href="profile_beneficiary.php" class="active">Profile</a></li><li><a href="../auth/logout.php" class="logout-link">Logout</a></li></ul></div>
    <div class="main-content">
        <div class="topbar"><div class="page-title">Profile</div><a href="edit_profile_beneficiary.php" class="btn-approve" style="text-decoration: none;">Edit Profile</a></div>
        <div class="content-wrapper"><div class="profile-card">
            <div class="profile-header-main"><div class="profile-avatar-large"><?php echo $initials; ?></div><div><h2><?php echo htmlspecialchars($data['B_Name']); ?></h2><div class="profile-status">Verified Beneficiary</div></div></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="info-group"><div class="info-text"><span class="info-label">Phone</span><span class="info-value"><?php echo !empty($data['B_Phone']) ? "+880 ".htmlspecialchars($data['B_Phone']) : 'N/A'; ?></span></div></div>
                <div class="info-group"><div class="info-text"><span class="info-label">Address</span><span class="info-value"><?php echo htmlspecialchars(($data['B_House_No'] ?? '') . ", " . ($data['B_Street_No'] ?? '') . ", " . ($data['B_City'] ?? '') . ", " . ($data['B_District'] ?? '')); ?></span></div></div>
            </div>
        </div></div>
    </div>
</body>
</html>