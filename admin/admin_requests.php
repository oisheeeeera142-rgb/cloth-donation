<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cr_id'])) {
    $cr_id = $_POST['cr_id'];
    $action = $_POST['action'];
    
    $stmt = $conn->prepare("UPDATE Clothing_Request SET Cr_Status = ?, A_Id = ? WHERE Cr_Id = ?");
    $stmt->bind_param("sii", $action, $user_id, $cr_id);
    
    if ($stmt->execute()) {
        $_SESSION['toast_success'] = "Request " . strtolower($action) . " successfully!";
    } else {
        $_SESSION['toast_error'] = "Error updating request.";
    }
    header("Location: admin_requests.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT cr.Cr_Id, cr.Cr_Date, cr.Cr_Cloth_Type, cr.Cr_Size, cr.Cr_Quantity, cr.Cr_Status, b.B_Name, b.B_NID 
    FROM Clothing_Request cr
    JOIN Beneficiary b ON cr.B_Id = b.B_Id
    ORDER BY cr.Cr_Date DESC, cr.Cr_Id DESC
");
$stmt->execute();
$requests = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Requests - ClotheCare</title>
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
            <div class="page-title">Review Requests</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container">
                <div class="table-header">
                    <div>
                        <h3 style="margin-bottom: 4px;">Beneficiary Requests</h3>
                        <div style="color: #666; font-size: 13px;">Review and take action on clothing requests</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Beneficiary Info</th>
                            <th>Item Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $requests->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #a3b8b2;">#<?php echo $row['Cr_Id']; ?></td>
                            <td>
                                <div style="font-weight: 700; color: #183e33;"><?php echo htmlspecialchars($row['B_Name']); ?></div>
                                <div style="font-size: 13px; color: #666;">NID: <?php echo htmlspecialchars($row['B_NID']); ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: #183e33;"><?php echo htmlspecialchars($row['Cr_Cloth_Type']); ?> (Qty: <?php echo htmlspecialchars($row['Cr_Quantity']); ?>)</div>
                                <div style="font-size: 13px; color: #666;">Size: <?php echo htmlspecialchars($row['Cr_Size']); ?> • Date: <?php echo htmlspecialchars($row['Cr_Date']); ?></div>
                            </td>
                            <td>
                                <?php 
                                $status_class = '';
                                $status = strtolower($row['Cr_Status']);
                                if($status === 'approved') $status_class = 'status-approved';
                                elseif($status === 'rejected') $status_class = 'status-rejected';
                                else $status_class = 'status-pending';
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($row['Cr_Status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($status === 'pending'): ?>
                                <form action="" method="POST" style="display: flex; gap: 8px; margin: 0;">
                                    <input type="hidden" name="cr_id" value="<?php echo $row['Cr_Id']; ?>">
                                    <button type="submit" name="action" value="Approved" class="btn-approve">Approve</button>
                                    <button type="submit" name="action" value="Rejected" class="btn-reject">Reject</button>
                                </form>
                                <?php else: ?>
                                <span style="color: #a3b8b2; font-weight: 700;">Action Taken</span>
                                <?php endif; ?>
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