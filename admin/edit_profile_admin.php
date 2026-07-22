<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') { header("Location: ../login.php"); exit(); }
$user_id = $_SESSION['user_id'];

$conn->query("CREATE TABLE IF NOT EXISTS Admin_Phone (A_Id INT PRIMARY KEY, A_Phone VARCHAR(20), FOREIGN KEY (A_Id) REFERENCES Admin(A_Id) ON DELETE CASCADE)");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);
    
    if(!empty($password)) { $conn->query("UPDATE Admin SET A_Password = '$password' WHERE A_Id = $user_id"); }
    
    $chk = $conn->query("SELECT * FROM Admin_Phone WHERE A_Id=$user_id");
    if($chk->num_rows > 0) { 
        $conn->query("UPDATE Admin_Phone SET A_Phone='$phone' WHERE A_Id=$user_id"); 
    } else { 
        $conn->query("INSERT INTO Admin_Phone (A_Id, A_Phone) VALUES ($user_id, '$phone')"); 
    }
    
    header("Location: profile_admin.php"); exit();
}

$data = $conn->query("SELECT a.A_Password, ap.A_Phone FROM Admin a LEFT JOIN Admin_Phone ap ON a.A_Id = ap.A_Id WHERE a.A_Id = $user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Admin Profile</title><link rel="stylesheet" href="../css/style.css"></head>
<body class="dashboard-body">
    <div class="sidebar"><div class="sidebar-brand">ClotheCare</div><ul class="nav-links"><li><a href="profile_admin.php">Back to Profile</a></li></ul></div>
    <div class="main-content"><div class="content-wrapper"><div class="table-container" style="padding:40px; max-width:600px; margin:auto; background:#ffffff; border-radius:24px;">
        <h2 style="margin-bottom:24px; color:#183e33;">Edit Profile</h2>
        <form method="POST">
            <div class="form-group"><label>Phone Number</label><input type="text" name="phone" value="<?php echo htmlspecialchars($data['A_Phone']??''); ?>" class="form-control" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" value="<?php echo htmlspecialchars($data['A_Password']); ?>" class="form-control" required></div>
            <div style="display:flex; gap:10px;"><button type="submit" class="btn-primary">Update Profile</button><button type="button" class="btn-reject" onclick="window.location.href='profile_admin.php'">Cancel</button></div>
        </form>
    </div></div></div>
</body>
</html>