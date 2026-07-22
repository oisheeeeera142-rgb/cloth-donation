<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];

$conn->query("CREATE TABLE IF NOT EXISTS Admin_Phone (A_Id INT PRIMARY KEY, A_Phone VARCHAR(20), FOREIGN KEY (A_Id) REFERENCES Admin(A_Id) ON DELETE CASCADE)");

$data = $conn->query("SELECT a.A_Name, a.A_Email, a.A_Password, ap.A_Phone FROM Admin a LEFT JOIN Admin_Phone ap ON a.A_Id = ap.A_Id WHERE a.A_Id = $user_id")->fetch_assoc();
$initials = strtoupper(substr(explode(' ', $data['A_Name'])[0], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Admin Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
        <ul class="nav-links">
    <li><a href="dashboard_admin.php">Dashboard</a></li>
    <li><a href="manage_inventory.php">Manage Inventory</a></li>
    <li><a href="admin_requests.php">Review Requests</a></li>
    <li><a href="admin_pickups.php">Assign Pickups</a></li>
    <li><a href="manage_users.php">Manage Users</a></li>
    <li><a href="profile_admin.php">Profile</a></li>
    <li><a href="../auth/logout.php" class="logout-link">Logout</a></li>
</ul>
    </div>
    <div class="main-content">
        <div class="topbar"><div class="page-title">Profile</div><a href="edit_profile_admin.php" class="btn-approve" style="text-decoration: none;">Edit Profile</a></div>
        <div class="content-wrapper"><div class="profile-card">
            <div class="profile-header-main"><div class="profile-avatar-large" style="background: linear-gradient(135deg, #E76F51 0%, #c45a40 100%);"><?php echo $initials; ?></div><div><h2><?php echo htmlspecialchars($data['A_Name']); ?></h2><div class="profile-status">System Admin</div></div></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="info-group"><div class="info-text"><span class="info-label">Email</span><span class="info-value"><?php echo htmlspecialchars($data['A_Email'] ?? 'N/A'); ?></span></div></div>
                <div class="info-group"><div class="info-text"><span class="info-label">Phone</span><span class="info-value"><?php echo !empty($data['A_Phone']) ? "+880 ".htmlspecialchars($data['A_Phone']) : 'N/A'; ?></span></div></div>
            </div>
        </div></div>
    </div>
</body>
</html>