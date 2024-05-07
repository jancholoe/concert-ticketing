<?php
include 'connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);

            // Store user information in session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['fullname'] = $row['fullname'];

            header('Location: admin.php');
        } else {
            echo "<script> alert('No matching user found.'); </script>";
        }
    } else {
        echo "Error executing query: " . mysqli_error($conn);
    }
}
?>
