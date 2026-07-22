<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'clothcare';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("DB Connection Error");
}
?>