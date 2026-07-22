<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Beneficiary') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

$stmt = $conn->prepare("
    SELECT Cr_Id, Cr_Cloth_Type, Cr_Size, Cr_Quantity, Cr_Date, Cr_Status 
    FROM Clothing_Request 
    WHERE B_Id = ? 
    ORDER BY Cr_Date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$requests = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - ClotheCare</title>
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
            <li><a href="dashboard_beneficiary.php">Dashboard</a></li>
            <li><a href="beneficiary_requests.php" class="active">My Requests</a></li>
            <li><a href="beneficiary_request_cloth.php">Request Clothes</a></li>
            <li><a href="profile_beneficiary.php">Profile</a></li>
            <li><a href="../auth/logout.php" class="logout-link">Logout</a></li>
        </ul>
        <div class="sidebar-profile">
            <div class="profile-avatar"><?php echo $initials; ?></div>
            <div class="profile-info">
                <span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="profile-role">Beneficiary</span>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="page-title">My Requests</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container">
                <div class="table-header">
                    <div>
                        <h3 style="margin-bottom: 4px;">My Clothing Requests</h3>
                        <div style="color: #666; font-size: 13px;">Track your request status</div>
                    </div>
                    <a href="beneficiary_request_cloth.php" style="padding: 10px 20px; background: #ffffff; border: 1.5px solid #264653; border-radius: 8px; color: #264653; font-weight: 700; text-decoration: none; font-size: 14px; transition: all 0.3s ease;" onmouseover="this.style.background='#f4f7f6'" onmouseout="this.style.background='#ffffff'">+ New Request</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Qty</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $requests->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: 700; color: #a3b8b2;">#<?php echo $row['Cr_Id']; ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['Cr_Date']); ?></td>
                            <td style="font-weight: 700;"><?php echo htmlspecialchars($row['Cr_Cloth_Type']); ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['Cr_Size']); ?></td>
                            <td style="font-weight: 700;"><?php echo htmlspecialchars($row['Cr_Quantity']); ?></td>
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
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>