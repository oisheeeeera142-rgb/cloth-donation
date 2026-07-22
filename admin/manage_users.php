<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $del_role = $_POST['del_role'];
    $del_id = $_POST['del_id'];
    
    try {
        if ($del_role === 'Donor') {
            $conn->query("DELETE FROM Donor_Email WHERE D_Id = $del_id");
            $conn->query("DELETE FROM Donor_Phone WHERE D_Id = $del_id");
            $conn->query("DELETE FROM Donor WHERE D_Id = $del_id");
        } elseif ($del_role === 'Volunteer') {
            $conn->query("DELETE FROM Volunteer_Phone WHERE V_Id = $del_id");
            $conn->query("DELETE FROM Volunteer WHERE V_Id = $del_id");
        } elseif ($del_role === 'Beneficiary') {
            $conn->query("DELETE FROM Beneficiary_Email WHERE B_Id = $del_id");
            $conn->query("DELETE FROM Beneficiary_Phone WHERE B_Id = $del_id");
            $conn->query("DELETE FROM Beneficiary WHERE B_Id = $del_id");
        }
        $_SESSION['toast_success'] = "$del_role removed successfully!";
    } catch (Exception $e) {
        $_SESSION['toast_error'] = "Cannot delete $del_role. They have active records!";
    }
    
    header("Location: manage_users.php");
    exit();
}

$donors = $conn->query("SELECT d.D_Id, d.D_Name, de.D_Email, dp.D_Phone FROM Donor d LEFT JOIN Donor_Email de ON d.D_Id = de.D_Id LEFT JOIN Donor_Phone dp ON d.D_Id = dp.D_Id GROUP BY d.D_Id");
$volunteers = $conn->query("SELECT v.V_Id, v.V_Name, v.V_Email, vp.V_Phone, v.V_Assigned_Area FROM Volunteer v LEFT JOIN Volunteer_Phone vp ON v.V_Id = vp.V_Id GROUP BY v.V_Id");
$beneficiaries = $conn->query("SELECT b.B_Id, b.B_Name, be.B_Email, bp.B_Phone, b.B_NID FROM Beneficiary b LEFT JOIN Beneficiary_Email be ON b.B_Id = be.B_Id LEFT JOIN Beneficiary_Phone bp ON b.B_Id = bp.B_Id GROUP BY b.B_Id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <?php if (isset($_SESSION['toast_success'])): ?>
        <div class="toast-container"><div class="toast toast-success"><?php echo $_SESSION['toast_success']; unset($_SESSION['toast_success']); ?></div></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['toast_error'])): ?>
        <div class="toast-container"><div class="toast toast-error"><?php echo $_SESSION['toast_error']; unset($_SESSION['toast_error']); ?></div></div>
    <?php endif; ?>

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
            <div class="page-title">Manage Users</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            
            <div class="table-container" style="margin-bottom: 48px;">
                <div class="table-header"><h3>Registered Donors</h3></div>
                <table>
                    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while($row = $donors->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #183e33;"><?php echo htmlspecialchars($row['D_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['D_Email'] ?? 'N/A'); ?></td>
                            <td style="font-weight: 600;">+880 <?php echo htmlspecialchars($row['D_Phone'] ?? 'N/A'); ?></td>
                            <td>
                                <form action="" method="POST" style="margin:0;" onsubmit="return confirm('Delete this donor?');">
                                    <input type="hidden" name="del_role" value="Donor">
                                    <input type="hidden" name="del_id" value="<?php echo $row['D_Id']; ?>">
                                    <button type="submit" name="delete_user" class="btn-icon-delete">
                                        <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container" style="margin-bottom: 48px;">
                <div class="table-header"><h3>Active Volunteers</h3></div>
                <table>
                    <thead><tr><th>Name</th><th>Email / Phone</th><th>Assigned Area</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while($row = $volunteers->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #183e33;"><?php echo htmlspecialchars($row['V_Name']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($row['V_Email']); ?></div>
                                <div style="font-size: 13px; color: #666; font-weight: 600;">+880 <?php echo htmlspecialchars($row['V_Phone'] ?? 'N/A'); ?></div>
                            </td>
                            <td style="font-weight: 600; color: #2A9D8F;"><?php echo htmlspecialchars($row['V_Assigned_Area'] ?? 'Not Assigned'); ?></td>
                            <td>
                                <form action="" method="POST" style="margin:0;" onsubmit="return confirm('Delete this volunteer?');">
                                    <input type="hidden" name="del_role" value="Volunteer">
                                    <input type="hidden" name="del_id" value="<?php echo $row['V_Id']; ?>">
                                    <button type="submit" name="delete_user" class="btn-icon-delete">
                                        <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-container">
                <div class="table-header"><h3>Beneficiaries</h3></div>
                <table>
                    <thead><tr><th>Name</th><th>NID Number</th><th>Contact Info</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while($row = $beneficiaries->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #183e33;"><?php echo htmlspecialchars($row['B_Name']); ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['B_NID']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($row['B_Email'] ?? 'No Email'); ?></div>
                                <div style="font-size: 13px; color: #666; font-weight: 600;">+880 <?php echo htmlspecialchars($row['B_Phone'] ?? 'N/A'); ?></div>
                            </td>
                            <td>
                                <form action="" method="POST" style="margin:0;" onsubmit="return confirm('Delete this beneficiary?');">
                                    <input type="hidden" name="del_role" value="Beneficiary">
                                    <input type="hidden" name="del_id" value="<?php echo $row['B_Id']; ?>">
                                    <button type="submit" name="delete_user" class="btn-icon-delete">
                                        <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>