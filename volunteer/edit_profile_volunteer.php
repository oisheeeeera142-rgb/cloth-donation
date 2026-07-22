<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Volunteer') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    $conn->query("UPDATE Volunteer SET V_Password='$password', V_Assigned_Area='$area' WHERE V_Id=$user_id");
    $chk = $conn->query("SELECT * FROM Volunteer_Phone WHERE V_Id=$user_id");
    if($chk->num_rows > 0) { $conn->query("UPDATE Volunteer_Phone SET V_Phone='$phone' WHERE V_Id=$user_id"); } 
    else { $conn->query("INSERT INTO Volunteer_Phone (V_Id, V_Phone) VALUES ($user_id, '$phone')"); }
    header("Location: profile_volunteer.php"); exit();
}
$data = $conn->query("SELECT v.V_Password, v.V_Assigned_Area, vp.V_Phone FROM Volunteer v LEFT JOIN Volunteer_Phone vp ON v.V_Id = vp.V_Id WHERE v.V_Id = $user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar"><div class="sidebar-brand">ClotheCare</div><ul class="nav-links"><li><a href="profile_volunteer.php">Back to Profile</a></li></ul></div>
    <div class="main-content"><div class="content-wrapper"><div class="table-container" style="padding:40px; max-width:600px; margin:auto; background:#ffffff; border-radius:24px;">
        <h2 style="margin-bottom:24px; color:#183e33;">Edit Profile</h2>
        <form method="POST">
            <div class="form-group"><label>Phone Number</label><input type="text" name="phone" value="<?php echo htmlspecialchars($data['V_Phone']??''); ?>" class="form-control" required></div>
            <div class="form-group">
                <label>Assigned Area</label>
                <select name="area" class="form-control" required>
                    <option value="Mirpur" <?php echo ($data['V_Assigned_Area']=='Mirpur')?'selected':''; ?>>Mirpur</option>
                    <option value="Dhanmondi" <?php echo ($data['V_Assigned_Area']=='Dhanmondi')?'selected':''; ?>>Dhanmondi</option>
                    <option value="Uttara" <?php echo ($data['V_Assigned_Area']=='Uttara')?'selected':''; ?>>Uttara</option>
                    <option value="Gulshan" <?php echo ($data['V_Assigned_Area']=='Gulshan')?'selected':''; ?>>Gulshan</option>
                </select>
            </div>
            <div class="form-group"><label>Password</label><input type="password" name="password" value="<?php echo htmlspecialchars($data['V_Password']); ?>" class="form-control" required></div>
            <div style="display:flex; gap:10px;"><button type="submit" class="btn-primary">Update Profile</button><button type="button" class="btn-reject" onclick="window.location.href='profile_volunteer.php'">Cancel</button></div>
        </form>
    </div></div></div>
</body>
</html>