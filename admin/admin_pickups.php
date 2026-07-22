<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') { header("Location: ../login.php"); exit(); }
$user_name = $_SESSION['user_name']; $initials = strtoupper(substr($user_name, 0, 1));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_vol'])) {
    $pr_id = $_POST['pr_id']; $v_id = $_POST['v_id'];
    $conn->query("UPDATE Pickup_Request SET V_Id = $v_id, Pr_Status = 'Assigned' WHERE Pr_Id = $pr_id");
    $_SESSION['toast_success'] = "Pickup Assigned to Volunteer!";
    header("Location: admin_pickups.php"); exit();
}

$reqs = $conn->query("SELECT pr.*, d.D_Name, dp.D_Phone FROM Pickup_Request pr JOIN Donor d ON pr.D_Id = d.D_Id LEFT JOIN Donor_Phone dp ON d.D_Id = dp.D_Id WHERE pr.Pr_Status = 'Pending' ORDER BY pr.Pr_Date ASC");

// Fetch Volunteers grouped by Area
$vols = $conn->query("SELECT V_Id, V_Name, V_Assigned_Area FROM Volunteer ORDER BY V_Assigned_Area, V_Name");
$vol_arr = [];
while($v = $vols->fetch_assoc()){ $vol_arr[$v['V_Assigned_Area']][] = $v; }
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Assign Pickups - ClotheCare</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <?php if (isset($_SESSION['toast_success'])): ?><div class="toast-container"><div class="toast toast-success"><?php echo $_SESSION['toast_success']; unset($_SESSION['toast_success']); ?></div></div><?php endif; ?>
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
            <div class="profile-info"><span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span><span class="profile-role">Admin</span></div>
        </div>
    </div>
    <div class="main-content">
        <div class="topbar"><div class="page-title">Assign Pickups to Volunteers</div><div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div></div>
        <div class="content-wrapper"><div class="table-container"><table>
            <thead><tr><th>ID</th><th>Donor Info</th><th>Time</th><th>Address</th><th>Assign Volunteer</th></tr></thead>
            <tbody>
                <?php while($r = $reqs->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $r['Pr_Id']; ?></td>
                    <td><b><?php echo $r['D_Name']; ?></b><br><small>+880 <?php echo $r['D_Phone']; ?></small></td>
                    <td><?php echo $r['Pr_Date']."<br>".$r['Pr_Time']; ?></td>
                    <td style="color:#2A9D8F; font-weight:bold;"><?php echo $r['Pr_Address']; ?></td>
                    <td>
                        <form method="POST" style="display:flex; gap:10px;">
                            <input type="hidden" name="pr_id" value="<?php echo $r['Pr_Id']; ?>">
                            <select name="v_id" class="form-control" style="padding:6px;" required>
                                <option value="">Select Volunteer</option>
                                <?php foreach($vol_arr as $area => $vs): ?>
                                    <optgroup label="<?php echo $area ? $area : 'Unassigned Area'; ?>">
                                    <?php foreach($vs as $v): ?><option value="<?php echo $v['V_Id']; ?>"><?php echo $v['V_Name']; ?></option><?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="assign_vol" class="btn-approve">Assign</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if($reqs->num_rows == 0): ?><tr><td colspan="5" style="text-align:center; padding:30px;">No pending pickups.</td></tr><?php endif; ?>
            </tbody>
        </table></div></div>
    </div>
</body>
</html>