<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Donor') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

$stmt = $conn->prepare("
    SELECT pr.Pr_Id, pr.Pr_Date, pr.Pr_Time, pr.Pr_Status, v.V_Name
    FROM Pickup_Request pr
    LEFT JOIN Volunteer v ON pr.V_Id = v.V_Id
    WHERE pr.D_Id = ?
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
    <title>Pickup Requests - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <?php if (isset($_SESSION['toast_success'])): ?>
        <div class="toast-container">
            <div class="toast toast-success"><?php echo $_SESSION['toast_success']; unset($_SESSION['toast_success']); ?></div>
        </div>
    <?php endif; ?>

    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
        <ul class="nav-links">
            <li><a href="dashboard_donor.php">Dashboard</a></li>
            <li><a href="donor_donations.php">My Donations</a></li>
            <li><a href="donor_pickups.php" class="active">Pickup Requests</a></li>
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
            <div class="page-title">Pickup Requests</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container">
                <div class="table-header">
                    <div>
                        <h3 style="margin-bottom: 4px;">Pickup Requests</h3>
                        <div style="color: #666; font-size: 13px;">Schedule a pickup for donated clothes</div>
                    </div>
                    <a href="donor_request_pickup.php" style="padding: 10px 20px; background: #ffffff; border: 1.5px solid #264653; border-radius: 8px; color: #264653; font-weight: 700; text-decoration: none; font-size: 14px; transition: all 0.3s ease;" onmouseover="this.style.background='#f4f7f6'" onmouseout="this.style.background='#ffffff'">+ New Request</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Volunteer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $pickups->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #a3b8b2;">#<?php echo $row['Pr_Id']; ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['Pr_Date']); ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['Pr_Time']); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($row['Pr_Status']) === 'completed' ? 'status-collected' : 'status-pending'; ?>">
                                    <?php echo htmlspecialchars($row['Pr_Status']); ?>
                                </span>
                            </td>
                            <td style="color: #666;"><?php echo htmlspecialchars($row['V_Name'] ?? 'Not assigned'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>