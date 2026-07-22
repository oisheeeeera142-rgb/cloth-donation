<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Donor') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['clothing_type'];
    $cat_id = $_POST['cat_id'];
    $size = $_POST['size'];
    $gender = $_POST['gender'];
    $condition = $_POST['condition'];
    $qty = $_POST['quantity'];
    $cen_id = $_POST['cen_id'];

    $res = $conn->query("SELECT IFNULL(MAX(Item_Id), 0) + 1 AS new_id FROM Clothing_Item");
    $row = $res->fetch_assoc();
    $new_item_id = $row['new_id'];

    $stmt = $conn->prepare("INSERT INTO Clothing_Item (Item_Id, C_Quantity, C_Size, Gender_Category, C_Condition, C_Clothing_Type, D_Id, Cat_Id, Cen_Id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssiii", $new_item_id, $qty, $size, $gender, $condition, $type, $user_id, $cat_id, $cen_id);
    
    if ($stmt->execute()) {
        $_SESSION['toast_success'] = "Donation added successfully!";
        header("Location: donor_donations.php");
        exit();
    } else {
        $_SESSION['toast_error'] = "Error adding donation.";
    }
}

$categories = $conn->query("SELECT Cat_Id, Cat_Name FROM Category");
$centers = $conn->query("SELECT Cen_Id, Cen_Name, Cen_Manager FROM Collection_Center");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Clothes - ClotheCare</title>
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
            <div class="page-title">Donate Clothes</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container" style="max-width: 600px; margin: 0 auto; padding: 36px;">
                <h2 style="color: #264653; margin-bottom: 8px; letter-spacing: -0.5px;">Donate Items</h2>
                <p style="color: #666; margin-bottom: 24px; font-size: 15px;">Tell us about the clothes you want to donate</p>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Clothing Type (e.g. Shirt, Pant, Jacket)</label>
                        <input type="text" name="clothing_type" class="form-control" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="cat_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php while($cat = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $cat['Cat_Id']; ?>"><?php echo htmlspecialchars($cat['Cat_Name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gender Category</label>
                            <select name="gender" class="form-control" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Kids">Kids</option>
                                <option value="Unisex">Unisex</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label>Size</label>
                            <select name="size" class="form-control" required>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Condition</label>
                            <select name="condition" class="form-control" required>
                                <option value="New">New</option>
                                <option value="Like New">Like New</option>
                                <option value="Good">Good</option>
                                <option value="Used">Used</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Drop-off Collection Center</label>
                        <select name="cen_id" class="form-control" required>
                            <option value="">Select nearest center</option>
                            <?php while($cen = $centers->fetch_assoc()): ?>
                                <option value="<?php echo $cen['Cen_Id']; ?>"><?php echo htmlspecialchars($cen['Cen_Name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary" style="margin-top: 16px;">Submit Donation</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>