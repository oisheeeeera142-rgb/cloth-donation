<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Volunteer') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_completed'])) {
    $pr_id = $_POST['pr_id'];
    $update_stmt = $conn->prepare("UPDATE Pickup_Request SET Pr_Status = 'Completed' WHERE Pr_Id = ? AND V_Id = ?");
    $update_stmt->bind_param("ii", $pr_id, $user_id);
    if ($update_stmt->execute()) {
        $_SESSION['toast_success'] = "Pickup marked as collected!";
        header("Location: volunteer_pickups.php");
        exit();
    }
}

$stmt = $conn->prepare("
    SELECT pr.Pr_Id, pr.Pr_Date, pr.Pr_Time, pr.Pr_Status, pr.Pr_Address, d.D_Name 
    FROM Pickup_Request pr
    JOIN Donor d ON pr.D_Id = d.D_Id
    WHERE pr.V_Id = ?
    ORDER BY pr.Pr_Date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pickups = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Pickups - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <?php if (isset($_SESSION['toast_success'])): ?>
        <div class="toast-container"><div class="toast toast-success"><?php echo $_SESSION['toast_success']; unset($_SESSION['toast_success']); ?></div></div>
    <?php endif; ?>

    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
        <ul class="nav-links">
            <li><a href="dashboard_volunteer.php">Dashboard</a></li>
            <li><a href="volunteer_pickups.php" class="active">My Pickups</a></li>
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
            <div class="page-title">My Pickups</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container">
                <div class="table-header"><h3>Pickup Assignments</h3></div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Donor</th>
                            <th>Date / Time</th>
                            <th>Pickup Address</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $pickups->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #a3b8b2;">#<?php echo $row['Pr_Id']; ?></td>
                            <td style="font-weight: 700; color: #183e33;"><?php echo htmlspecialchars($row['D_Name']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($row['Pr_Date']); ?></div>
                                <div style="font-size: 13px; color: #666; font-weight: 600;"><?php echo htmlspecialchars($row['Pr_Time']); ?></div>
                            </td>
                            <td style="font-weight: 600; color: #2A9D8F; font-size: 14px;">
                                <?php echo htmlspecialchars($row['Pr_Address']); ?>
                            </td>
                            <td>
                                <span class="status-badge <?php echo strtolower($row['Pr_Status']) === 'completed' ? 'status-collected' : 'status-pending'; ?>">
                                    <?php echo htmlspecialchars($row['Pr_Status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if(strtolower($row['Pr_Status']) !== 'completed'): ?>
                                <form action="" method="POST" style="margin: 0;">
                                    <input type="hidden" name="pr_id" value="<?php echo $row['Pr_Id']; ?>">
                                    <button type="submit" name="mark_completed" class="btn-approve" style="padding: 8px 12px; font-size: 13px;">Mark Collected</button>
                                </form>
                                <?php else: ?>
                                <span style="color: #a3b8b2; font-weight: 700;">Done</span>
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