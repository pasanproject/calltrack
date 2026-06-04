<?php
include 'config.php';

if (isset($_POST['id']) && isset($_POST['lat'])) {
    $id = $_POST['id'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $stmt = $conn->prepare("UPDATE call_trackings SET latitude=?, longitude=?, is_verified=1 WHERE unique_id=?");
    $stmt->bind_param("dds", $lat, $lng, $id);
    $stmt->execute();
}
?>