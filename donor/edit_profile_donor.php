<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Donor') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $da_id = $conn->query("SELECT DA_Id FROM Donor WHERE D_Id = $user_id")->fetch_assoc()['DA_Id'];
    if ($da_id) { $conn->query("UPDATE Donor_Address SET D_House_No='$house', D_Street_No='$street', D_City='$city', D_District='$district' WHERE DA_Id=$da_id"); } 
    else {
        $nid = $conn->query("SELECT IFNULL(MAX(DA_Id), 0) + 1 AS n FROM Donor_Address")->fetch_assoc()['n'];
        $conn->query("INSERT INTO Donor_Address VALUES ($nid, '$house', '$street', '$city', '$district')");
        $conn->query("UPDATE Donor SET DA_Id = $nid WHERE D_Id = $user_id");
    }
    if(!empty($password)) { $conn->query("UPDATE Donor SET D_Password = '$password' WHERE D_Id = $user_id"); }
    $chk_phone = $conn->query("SELECT * FROM Donor_Phone WHERE D_Id=$user_id");
    if($chk_phone->num_rows > 0) { $conn->query("UPDATE Donor_Phone SET D_Phone='$phone' WHERE D_Id=$user_id"); } 
    else { $conn->query("INSERT INTO Donor_Phone (D_Id, D_Phone) VALUES ($user_id, '$phone')"); }
    header("Location: profile_donor.php"); exit();
}
$data = $conn->query("SELECT d.D_Password, dp.D_Phone, da.* FROM Donor d LEFT JOIN Donor_Phone dp ON d.D_Id = dp.D_Id LEFT JOIN Donor_Address da ON d.DA_Id = da.DA_Id WHERE d.D_Id = $user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar"><div class="sidebar-brand">ClotheCare</div><ul class="nav-links"><li><a href="profile_donor.php">Back to Profile</a></li></ul></div>
    <div class="main-content"><div class="content-wrapper"><div class="table-container" style="padding:40px; max-width:600px; margin:auto; background:#ffffff; border-radius:24px;">
        <h2 style="margin-bottom:24px; color:#183e33;">Edit Profile</h2>
        <form method="POST">
            <div class="form-group"><label>Phone Number</label><input type="text" name="phone" value="<?php echo htmlspecialchars($data['D_Phone']??''); ?>" class="form-control" required></div>
            <div class="form-group"><label>House No</label><input type="text" name="house" value="<?php echo htmlspecialchars($data['D_House_No']??''); ?>" class="form-control" required></div>
            <div class="form-group"><label>Street / Road</label><input type="text" name="street" value="<?php echo htmlspecialchars($data['D_Street_No']??''); ?>" class="form-control" required></div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group"><label>City</label><input type="text" name="city" value="<?php echo htmlspecialchars($data['D_City']??''); ?>" class="form-control" required></div>
                <div class="form-group"><label>District</label><input type="text" name="district" value="<?php echo htmlspecialchars($data['D_District']??''); ?>" class="form-control" required></div>
            </div>
            <div class="form-group"><label>Password</label><input type="password" name="password" value="<?php echo htmlspecialchars($data['D_Password']); ?>" class="form-control" required></div>
            <div style="display:flex; gap:10px;"><button type="submit" class="btn-primary">Update Profile</button><button type="button" class="btn-reject" onclick="window.location.href='profile_donor.php'">Cancel</button></div>
        </form>
    </div></div></div>
</body>
</html>