<?php
include 'config.php';

if (isset($_POST['phone_number'])) {
    $number = $_POST['phone_number'];
    $name = $_POST['contact_name'];
    $unique_id = bin2hex(random_bytes(8)); // අද්විතීය ID එකක් සාදයි

    $stmt = $conn->prepare("INSERT INTO call_trackings (phone_number, contact_name, unique_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $number, $name, $unique_id);
    
    if ($stmt->execute()) {
        // ඇමතුම්කරුට යැවිය යුතු ලින්ක් එක Output කරයි
        echo "http://yourdomain.com/track.php?id=" . $unique_id;
    }
}
?>