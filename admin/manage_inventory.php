<?php
require_once '../config/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$initials = strtoupper(substr(explode(' ', $user_name)[0], 0, 1) . (isset(explode(' ', $user_name)[1]) ? substr(explode(' ', $user_name)[1], 0, 1) : ''));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_item'])) {
    $del_id = $_POST['item_id'];
    $conn->query("DELETE FROM Item_Beneficiary WHERE Item_Id = $del_id");
    $stmt = $conn->prepare("DELETE FROM Clothing_Item WHERE Item_Id = ?");
    $stmt->bind_param("i", $del_id);
    
    if ($stmt->execute()) {
        $_SESSION['toast_success'] = "Item marked as distributed and removed!";
    } else {
        $_SESSION['toast_error'] = "Error removing item.";
    }
    header("Location: manage_inventory.php");
    exit();
}

$filter_cat = $_GET['category'] ?? '';
$query = "
    SELECT ci.Item_Id, ci.C_Clothing_Type, ci.C_Size, ci.C_Condition, ci.C_Quantity, ci.Gender_Category, d.D_Name, c.Cat_Name
    FROM Clothing_Item ci
    LEFT JOIN Donor d ON ci.D_Id = d.D_Id
    LEFT JOIN Category c ON ci.Cat_Id = c.Cat_Id
";
if ($filter_cat !== '') {
    $query .= " WHERE ci.Cat_Id = '" . $conn->real_escape_string($filter_cat) . "'";
}
$query .= " ORDER BY ci.Item_Id DESC";
$inventory = $conn->query($query);

$categories = $conn->query("SELECT Cat_Id, Cat_Name FROM Category");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory - ClotheCare</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-body">

    <?php if (isset($_SESSION['toast_success'])): ?>
        <div class="toast-container"><div class="toast toast-success"><?php echo $_SESSION['toast_success']; unset($_SESSION['toast_success']); ?></div></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['toast_error'])): ?>
        <div class="toast-container"><div class="toast toast-error"><?php echo $_SESSION['toast_error']; unset($_SESSION['toast_error']); ?></div></div>
    <?php endif; ?>

    <div class="sidebar">
        <div class="sidebar-brand">ClotheCare</div>
       <ul class="nav-links">
    <li><a href="dashboard_admin.php">Dashboard</a></li>
    <li><a href="manage_inventory.php">Manage Inventory</a></li>
    <li><a href="admin_requests.php">Review Requests</a></li>
    <li><a href="admin_pickups.php">Assign Pickups</a></li>
    <li><a href="manage_users.php">Manage Users</a></li>
    <li><a href="profile_admin.php">Profile</a></li>
    <li><a href="../auth/logout.php" class="logout-link">Logout</a></li>
</ul>
        <div class="sidebar-profile">
            <div class="profile-avatar" style="background: linear-gradient(135deg, #E76F51 0%, #c45a40 100%);"><?php echo $initials; ?></div>
            <div class="profile-info">
                <span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="profile-role">Admin</span>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="page-title">Manage Inventory</div>
            <div class="topbar-user">Welcome, <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="content-wrapper">
            <div class="table-container">
                <div class="table-header" style="flex-wrap: wrap; gap: 16px;">
                    <div>
                        <h3 style="margin-bottom: 4px;">Donated Clothes Inventory</h3>
                        <div style="color: #666; font-size: 13px;">Organized view of all available items</div>
                    </div>
                    
                    <form action="" method="GET" style="display: flex; gap: 8px; align-items: center;">
                        <select name="category" class="form-control" style="padding: 8px 12px; margin: 0; min-width: 150px;" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php while($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['Cat_Id']; ?>" <?php echo $filter_cat == $cat['Cat_Id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['Cat_Name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <?php if($filter_cat !== ''): ?>
                            <a href="manage_inventory.php" style="color: #E76F51; font-weight: 700; text-decoration: none; font-size: 14px; margin-left: 8px;">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Donor</th>
                            <th>Details</th>
                            <th>Category</th>
                            <th>Condition</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $inventory->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <span style="background: #e0f2f0; color: #1e7a6f; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-family: monospace; letter-spacing: 1px;">
                                    CLN-<?php echo str_pad($row['Item_Id'], 4, '0', STR_PAD_LEFT); ?>
                                </span>
                            </td>
                            <td style="font-weight: 700; color: #183e33;"><?php echo htmlspecialchars($row['D_Name'] ?? 'Unknown'); ?></td>
                            <td>
                                <div style="font-weight: 700;"><?php echo htmlspecialchars($row['C_Clothing_Type']); ?> (Qty: <?php echo htmlspecialchars($row['C_Quantity']); ?>)</div>
                                <div style="font-size: 13px; color: #666;">Size: <?php echo htmlspecialchars($row['C_Size']); ?> • <?php echo htmlspecialchars($row['Gender_Category']); ?></div>
                            </td>
                            <td style="font-weight: 600; color: #2196F3;"><?php echo htmlspecialchars($row['Cat_Name'] ?? 'N/A'); ?></td>
                            <td style="font-weight: 600; color: #F4A261;"><?php echo htmlspecialchars($row['C_Condition']); ?></td>
                            <td>
                                <button type="button" class="btn-danger" onclick="openDeleteModal(<?php echo $row['Item_Id']; ?>)">Give Away / Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if($inventory->num_rows == 0): ?>
                        <tr><td colspan="6" style="text-align: center; padding: 32px; color: #666;">No items found in inventory.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <h3 class="modal-title">Confirm Action</h3>
            <p class="modal-text">Are you sure you want to mark this item as distributed and remove it from the inventory?</p>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <form action="" method="POST" style="margin:0;">
                    <input type="hidden" name="item_id" id="modalItemId" value="">
                    <button type="submit" name="delete_item" class="btn-confirm-danger">Yes, Remove</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id) {
            document.getElementById('modalItemId').value = id;
            document.getElementById('deleteModal').classList.add('active');
        }
        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
    </script>

</body>
</html>