<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = trim($_POST['role']); 
    $name = trim($_POST['name']); 
    $email = trim($_POST['email']); 
    $phone = trim($_POST['phone']);
    $password = $_POST['password']; // PLAIN TEXT
    $house = trim($_POST['house'] ?? ''); 
    $street = trim($_POST['street'] ?? '');
    $city = trim($_POST['city'] ?? ''); 
    $district = trim($_POST['district'] ?? ''); 
    $area = $_POST['area'] ?? null;
    $success = false;

    if ($role === 'Admin') {
        $id = $conn->query("SELECT IFNULL(MAX(A_Id), 0) + 1 AS n FROM Admin")->fetch_assoc()['n'];
        $stmt = $conn->prepare("INSERT INTO Admin (A_Id, A_Name, A_Email, A_Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id, $name, $email, $password);
        if ($stmt->execute()) $success = true;
    } 
    elseif ($role === 'Donor') {
        // Address
        $aid = $conn->query("SELECT IFNULL(MAX(DA_Id), 0) + 1 AS n FROM Donor_Address")->fetch_assoc()['n'];
        $stmt_addr = $conn->prepare("INSERT INTO Donor_Address (DA_Id, D_House_No, D_Street_No, D_City, D_District) VALUES (?, ?, ?, ?, ?)");
        $stmt_addr->bind_param("issss", $aid, $house, $street, $city, $district);
        $stmt_addr->execute();
        
        // User
        $id = $conn->query("SELECT IFNULL(MAX(D_Id), 0) + 1 AS n FROM Donor")->fetch_assoc()['n'];
        $stmt_user = $conn->prepare("INSERT INTO Donor (D_Id, D_Name, D_Password, DA_Id) VALUES (?, ?, ?, ?)");
        $stmt_user->bind_param("issi", $id, $name, $password, $aid);
        $stmt_user->execute();
        
        // Email & Phone
        $stmt_em = $conn->prepare("INSERT INTO Donor_Email (D_Id, D_Email) VALUES (?, ?)");
        $stmt_em->bind_param("is", $id, $email);
        $stmt_em->execute();
        
        $stmt_ph = $conn->prepare("INSERT INTO Donor_Phone (D_Id, D_Phone) VALUES (?, ?)");
        $stmt_ph->bind_param("is", $id, $phone);
        if ($stmt_ph->execute()) $success = true;
    } 
    elseif ($role === 'Volunteer') {
        $id = $conn->query("SELECT IFNULL(MAX(V_Id), 0) + 1 AS n FROM Volunteer")->fetch_assoc()['n'];
        $stmt_user = $conn->prepare("INSERT INTO Volunteer (V_Id, V_Name, V_Password, V_Email, V_Assigned_Area) VALUES (?, ?, ?, ?, ?)");
        $stmt_user->bind_param("issss", $id, $name, $password, $email, $area);
        $stmt_user->execute();
        
        $stmt_ph = $conn->prepare("INSERT INTO Volunteer_Phone (V_Id, V_Phone) VALUES (?, ?)");
        $stmt_ph->bind_param("is", $id, $phone);
        if ($stmt_ph->execute()) $success = true;
    } 
    elseif ($role === 'Beneficiary') {
        // Address
        $aid = $conn->query("SELECT IFNULL(MAX(BA_Id), 0) + 1 AS n FROM Beneficiary_Address")->fetch_assoc()['n'];
        $stmt_addr = $conn->prepare("INSERT INTO Beneficiary_Address (BA_Id, B_House_No, B_Street_No, B_City, B_District) VALUES (?, ?, ?, ?, ?)");
        $stmt_addr->bind_param("issss", $aid, $house, $street, $city, $district);
        $stmt_addr->execute();
        
        // User
        $id = $conn->query("SELECT IFNULL(MAX(B_Id), 0) + 1 AS n FROM Beneficiary")->fetch_assoc()['n'];
        $status = 'Pending';
        $stmt_user = $conn->prepare("INSERT INTO Beneficiary (B_Id, B_Name, B_Password, B_NID, BA_Id) VALUES (?, ?, ?, ?, ?)");
        $stmt_user->bind_param("isssi", $id, $name, $password, $status, $aid);
        $stmt_user->execute();
        
        // Email & Phone
        $stmt_em = $conn->prepare("INSERT INTO Beneficiary_Email (B_Id, B_Email) VALUES (?, ?)");
        $stmt_em->bind_param("is", $id, $email);
        $stmt_em->execute();
        
        $stmt_ph = $conn->prepare("INSERT INTO Beneficiary_Phone (B_Id, B_Phone) VALUES (?, ?)");
        $stmt_ph->bind_param("is", $id, $phone);
        if ($stmt_ph->execute()) $success = true;
    }

    if ($success) {
        $_SESSION['toast_success'] = "Registration successful! Please sign in.";
        header("Location: ../login.php");
    } else {
        $_SESSION['toast_error'] = "Registration failed. Please check your details.";
        header("Location: ../register.php");
    }
    exit();
}
?>