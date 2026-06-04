<?php
$host = "localhost";
$user = "root"; // ඔබගේ DB Username
$pass = "";     // ඔබගේ DB Password
$dbname = "call_tracking_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>