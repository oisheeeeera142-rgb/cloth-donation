<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Beneficiary') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['cloth_type'];
    $size = $_POST['size'];
    $qty = $_POST['quantity'];
    $date = date('Y-m-d');
    $status = 'Pending';
    $admin_id = null;

    $res = $conn->query("SELECT IFNULL(MAX(Cr_Id), 0) + 1 AS new_id FROM Clothing_Request");
    $row = $res->fetch_assoc();
    $new_cr_id = $row['new_id'];

    $stmt = $conn->prepare("INSERT INTO Clothing_Request (Cr_Id, Cr_Cloth_Type, Cr_Size, Cr_Quantity, Cr_Date, Cr_Status, B_Id, A_Id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississii", $new_cr_id, $type, $size, $qty, $date, $status, $user_id, $admin_id);
    
    if ($stmt->execute()) {
        $_SESSION['toast_success'] = "Clothing request submitted successfully!";
        header("Location: beneficiary_requests.php");
        exit();
    } else {
        $_SESSION['toast_error'] = "Error submitting request.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Clothes - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <?php if (isset($_SESSION['toast_error'])): ?>
        <div class="toast-container">
            <div class="toast toast-error"><?php echo $_SESSION['toast_error']; unset($_SESSION['toast_error']); ?></div>
        </div>
    <?php endif; ?>

    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
        <ul class="nav-links">
            <li><a href="dashboard_beneficiary.php">Dashboard</a></li>
            <li><a href="beneficiary_requests.php">My Requests</a></li>
            <li><a href="beneficiary_request_cloth.php" class="active">Request Clothes</a></li>
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
            <div class="page-title">Request Clothes</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container" style="max-width: 500px; margin: 0 auto; padding: 36px;">
                <h2 style="color: #264653; margin-bottom: 8px; letter-spacing: -0.5px;">Request Clothes</h2>
                <p style="color: #666; margin-bottom: 24px; font-size: 15px;">Tell us what you need</p>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Clothing Type</label>
                        <select name="cloth_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="Shirt">Shirt</option>
                            <option value="Pant">Pant</option>
                            <option value="Jacket">Jacket</option>
                            <option value="Sweater">Sweater</option>
                            <option value="T-Shirt">T-Shirt</option>
                            <option value="Salwar">Salwar</option>
                            <option value="Sharee">Sharee</option>
                            <option value="Blanket">Blanket</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label>Size</label>
                            <select name="size" class="form-control" required>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="Free">Free Size</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" max="10" value="1" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" style="margin-top: 16px;">Submit Request</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>