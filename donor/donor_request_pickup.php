<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Donor') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

// Fetch Current Profile Address for Display (with null check)
$addr_res = $conn->query("
    SELECT da.D_House_No, da.D_Street_No, da.D_City 
    FROM Donor d 
    LEFT JOIN Donor_Address da ON d.DA_Id = da.DA_Id 
    WHERE d.D_Id = $user_id LIMIT 1
");

$profile_addr = $addr_res->fetch_assoc();

if ($profile_addr && !empty($profile_addr['D_House_No'])) {
    $saved_addr_str = $profile_addr['D_House_No'] . ", " . $profile_addr['D_Street_No'] . ", " . $profile_addr['D_City'];
} else {
    $saved_addr_str = "No address saved in profile.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['pickup_date'];
    $time = $_POST['pickup_time'];
    $addr_type = $_POST['address_type'];
    
    // Ensure final address is not empty if they chose saved but it's null
    if ($addr_type === 'saved') {
        $final_address = ($saved_addr_str !== "No address saved in profile.") ? $saved_addr_str : $_POST['manual_address'];
    } else {
        $final_address = $_POST['manual_address'];
    }
    
    $status = 'Pending';

    $res = $conn->query("SELECT IFNULL(MAX(Pr_Id), 0) + 1 AS new_id FROM Pickup_Request");
    $row = $res->fetch_assoc();
    $new_pr_id = $row['new_id'];

    $stmt = $conn->prepare("INSERT INTO Pickup_Request (Pr_Id, Pr_Date, Pr_Time, Pr_Status, D_Id, Pr_Address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $new_pr_id, $date, $time, $status, $user_id, $final_address);
    
    if ($stmt->execute()) {
        $_SESSION['toast_success'] = "Pickup scheduled successfully!";
        header("Location: donor_pickups.php");
        exit();
    } else {
        $_SESSION['toast_error'] = "Error scheduling pickup.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Pickup - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <?php if (isset($_SESSION['toast_error'])): ?>
        <div class="toast-container"><div class="toast toast-error"><?php echo $_SESSION['toast_error']; unset($_SESSION['toast_error']); ?></div></div>
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
            <div class="page-title">Request Pickup</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container" style="max-width: 600px; margin: 0 auto; padding: 36px;">
                <h2 style="color: #264653; margin-bottom: 8px; letter-spacing: -0.5px;">Schedule a Pickup</h2>
                <p style="color: #666; margin-bottom: 32px; font-size: 15px;">Where should the volunteer collect the clothes from?</p>
                
                <form action="" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                        <div class="form-group">
                            <label>Pickup Date</label>
                            <input type="date" name="pickup_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Preferred Time</label>
                            <select name="pickup_time" class="form-control" required>
                                <option value="">Select Time</option>
                                <option value="09:00 AM">09:00 AM - 11:00 AM</option>
                                <option value="02:00 PM">02:00 PM - 04:00 PM</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Pickup Address</label>
                        <div class="role-selector" style="margin-bottom: 16px; grid-template-columns: 1fr 1fr;">
                            <input type="radio" id="addr-saved" name="address_type" value="saved" onclick="toggleManualAddr(false)" <?php echo ($saved_addr_str !== "No address saved in profile.") ? 'checked' : ''; ?>>
                            <label for="addr-saved" class="role-label">Saved Address</label>

                            <input type="radio" id="addr-manual" name="address_type" value="manual" onclick="toggleManualAddr(true)" <?php echo ($saved_addr_str === "No address saved in profile.") ? 'checked' : ''; ?>>
                            <label for="addr-manual" class="role-label">Manual Address</label>
                        </div>
                        
                        <div id="saved-addr-preview" style="background: #f8f9fa; padding: 16px; border-radius: 12px; border: 2px solid #edf2f0; color: #4a655f; font-weight: 600; font-size: 14px; <?php echo ($saved_addr_str === "No address saved in profile.") ? 'display:none;' : ''; ?>">
                            <span style="font-size: 12px; color: #a3b8b2; display: block; margin-bottom: 4px;">Current Profile Address:</span>
                            <?php echo htmlspecialchars($saved_addr_str); ?>
                        </div>

                        <div id="manual-addr-input" style="<?php echo ($saved_addr_str !== "No address saved in profile.") ? 'display:none;' : 'margin-top: 16px;'; ?>">
                            <textarea name="manual_address" class="form-control" style="height: 100px;" placeholder="Enter complete pickup address here..." <?php echo ($saved_addr_str === "No address saved in profile.") ? 'required' : ''; ?>></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" style="margin-top: 16px;">Confirm Pickup</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleManualAddr(show) {
            const preview = document.getElementById('saved-addr-preview');
            const input = document.getElementById('manual-addr-input');
            const textArea = input.querySelector('textarea');
            
            if (show) {
                preview.style.display = 'none';
                input.style.display = 'block';
                textArea.required = true;
            } else {
                preview.style.display = 'block';
                input.style.display = 'none';
                textArea.required = false;
                textArea.value = '';
            }
        }
    </script>

</body>
</html>