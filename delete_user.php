<?php
include 'connection.php';
if (isset($_GET['Id'])) {
    $id = $_GET['Id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User deleted successfully'); window.location='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user'); window.history.back();</script>";
    }
}
?>
