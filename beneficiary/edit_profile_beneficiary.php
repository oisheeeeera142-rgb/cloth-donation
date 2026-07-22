<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Beneficiary') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $ba_id = $conn->query("SELECT BA_Id FROM Beneficiary WHERE B_Id = $user_id")->fetch_assoc()['BA_Id'];
    if ($ba_id) { $conn->query("UPDATE Beneficiary_Address SET B_House_No='$house', B_Street_No='$street', B_City='$city', B_District='$district' WHERE BA_Id=$ba_id"); } 
    else {
        $nid = $conn->query("SELECT IFNULL(MAX(BA_Id), 0) + 1 AS n FROM Beneficiary_Address")->fetch_assoc()['n'];
        $conn->query("INSERT INTO Beneficiary_Address VALUES ($nid, '$house', '$street', '$city', '$district')");
        $conn->query("UPDATE Beneficiary SET BA_Id = $nid WHERE B_Id = $user_id");
    }
    if(!empty($password)) { $conn->query("UPDATE Beneficiary SET B_Password = '$password' WHERE B_Id = $user_id"); }
    $chk = $conn->query("SELECT * FROM Beneficiary_Phone WHERE B_Id=$user_id");
    if($chk->num_rows > 0) { $conn->query("UPDATE Beneficiary_Phone SET B_Phone='$phone' WHERE B_Id=$user_id"); } 
    else { $conn->query("INSERT INTO Beneficiary_Phone (B_Id, B_Phone) VALUES ($user_id, '$phone')"); }
    header("Location: profile_beneficiary.php"); exit();
}
$data = $conn->query("SELECT b.B_Password, bp.B_Phone, ba.* FROM Beneficiary b LEFT JOIN Beneficiary_Phone bp ON b.B_Id = bp.B_Id LEFT JOIN Beneficiary_Address ba ON b.BA_Id = ba.BA_Id WHERE b.B_Id = $user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar"><div class="sidebar-brand">ClotheCare</div><ul class="nav-links"><li><a href="profile_beneficiary.php">Back to Profile</a></li></ul></div>
    <div class="main-content"><div class="content-wrapper"><div class="table-container" style="padding:40px; max-width:600px; margin:auto; background:#ffffff; border-radius:24px;">
        <h2 style="margin-bottom:24px; color:#183e33;">Edit Profile</h2>
        <form method="POST">
            <div class="form-group"><label>Phone Number</label><input type="text" name="phone" value="<?php echo htmlspecialchars($data['B_Phone']??''); ?>" class="form-control" required></div>
            <div class="form-group"><label>House No</label><input type="text" name="house" value="<?php echo htmlspecialchars($data['B_House_No']??''); ?>" class="form-control" required></div>
            <div class="form-group"><label>Street / Road</label><input type="text" name="street" value="<?php echo htmlspecialchars($data['B_Street_No']??''); ?>" class="form-control" required></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group"><label>City</label><input type="text" name="city" value="<?php echo htmlspecialchars($data['B_City']??''); ?>" class="form-control" required></div>
                <div class="form-group"><label>District</label><input type="text" name="district" value="<?php echo htmlspecialchars($data['B_District']??''); ?>" class="form-control" required></div>
            </div>
            <div class="form-group"><label>Password</label><input type="password" name="password" value="<?php echo htmlspecialchars($data['B_Password']); ?>" class="form-control" required></div>
            <div style="display:flex; gap:10px;"><button type="submit" class="btn-primary">Update Profile</button><button type="button" class="btn-reject" onclick="window.location.href='profile_beneficiary.php'">Cancel</button></div>
        </form>
    </div></div></div>
</body>
</html>