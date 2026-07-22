<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); 
    $password = $_POST['password']; 
    $role = trim($_POST['role']);
    $user_id = null; 
    $user_name = ""; 
    $auth = false;

    if ($role === 'Donor') {
        $stmt = $conn->prepare("SELECT d.D_Id, d.D_Name, d.D_Password FROM Donor d JOIN Donor_Email de ON d.D_Id = de.D_Id WHERE de.D_Email = ? LIMIT 1");
        $stmt->bind_param("s", $email); 
        $stmt->execute(); 
        $r = $stmt->get_result();
        if ($row = $r->fetch_assoc()) { 
            if ($password === $row['D_Password']) { 
                $user_id = $row['D_Id']; 
                $user_name = $row['D_Name']; 
                $auth = true; 
            } 
        }
    } elseif ($role === 'Volunteer') {
        $stmt = $conn->prepare("SELECT V_Id, V_Name, V_Password FROM Volunteer WHERE V_Email = ? LIMIT 1");
        $stmt->bind_param("s", $email); 
        $stmt->execute(); 
        $r = $stmt->get_result();
        if ($row = $r->fetch_assoc()) { 
            if ($password === $row['V_Password']) { 
                $user_id = $row['V_Id']; 
                $user_name = $row['V_Name']; 
                $auth = true; 
            } 
        }
    } elseif ($role === 'Beneficiary') {
        $stmt = $conn->prepare("SELECT b.B_Id, b.B_Name, b.B_Password FROM Beneficiary b JOIN Beneficiary_Email be ON b.B_Id = be.B_Id WHERE be.B_Email = ? LIMIT 1");
        $stmt->bind_param("s", $email); 
        $stmt->execute(); 
        $r = $stmt->get_result();
        if ($row = $r->fetch_assoc()) { 
            if ($password === $row['B_Password']) { 
                $user_id = $row['B_Id']; 
                $user_name = $row['B_Name']; 
                $auth = true; 
            } 
        }
    } elseif ($role === 'Admin') {
        $stmt = $conn->prepare("SELECT A_Id, A_Name, A_Password FROM Admin WHERE A_Email = ? LIMIT 1");
        $stmt->bind_param("s", $email); 
        $stmt->execute(); 
        $r = $stmt->get_result();
        if ($row = $r->fetch_assoc()) { 
            if ($password === $row['A_Password']) { 
                $user_id = $row['A_Id']; 
                $user_name = $row['A_Name']; 
                $auth = true; 
            } 
        }
    }

    if ($auth) {
        $_SESSION['user_id'] = $user_id; 
        $_SESSION['user_name'] = $user_name; 
        $_SESSION['role'] = $role;
        $folder = strtolower($role); 
        header("Location: ../{$folder}/dashboard_{$folder}.php");
    } else {
        $_SESSION['toast_error'] = "Invalid Email or Password!"; 
        header("Location: ../login.php");
    }
    exit();
}
?>