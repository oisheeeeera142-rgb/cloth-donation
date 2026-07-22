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
    SELECT ci.Item_Id, ci.C_Clothing_Type, c.Cat_Name, ci.C_Size, ci.C_Condition, ci.C_Quantity, cc.Cen_Name
    FROM Clothing_Item ci
    LEFT JOIN Category c ON ci.Cat_Id = c.Cat_Id
    LEFT JOIN Collection_Center cc ON ci.Cen_Id = cc.Cen_Id
    WHERE ci.D_Id = ?
    ORDER BY ci.Item_Id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$donations = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Donations - ClotheCare</title>
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
            <li><a href="donor_donations.php" class="active">My Donations</a></li>
            <li><a href="donor_pickups.php">Pickup Requests</a></li>
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
            <div class="page-title">My Donations</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container">
                <div class="table-header">
                    <div>
                        <h3 style="margin-bottom: 4px;">My Donations</h3>
                        <div style="color: #666; font-size: 13px;">All items you have donated</div>
                    </div>
                    <a href="donor_donate.php" style="padding: 10px 20px; background: #ffffff; border: 1.5px solid #264653; border-radius: 8px; color: #264653; font-weight: 700; text-decoration: none; font-size: 14px; transition: all 0.3s ease;" onmouseover="this.style.background='#f4f7f6'" onmouseout="this.style.background='#ffffff'">+ Donate More</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Condition</th>
                            <th>Qty</th>
                            <th>Center</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $donations->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #a3b8b2;">#<?php echo $row['Item_Id']; ?></td>
                            <td style="font-weight: 700;"><?php echo htmlspecialchars($row['C_Clothing_Type']); ?></td>
                            <td style="color: #2196F3; font-weight: 600;"><?php echo htmlspecialchars($row['Cat_Name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['C_Size']); ?></td>
                            <td style="color: #F4A261; font-weight: 600;"><?php echo htmlspecialchars($row['C_Condition']); ?></td>
                            <td style="font-weight: 700;"><?php echo htmlspecialchars($row['C_Quantity']); ?></td>
                            <td style="color: #666;"><?php echo htmlspecialchars($row['Cen_Name'] ?? 'N/A'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>