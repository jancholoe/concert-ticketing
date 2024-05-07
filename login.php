<?php
include 'connection.php';
function logEvent($conn, $userId, $eventType, $description) {
    $sql = "INSERT INTO logs (user_id, event_type, description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $userId, $eventType, $description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $inputPassword = $_POST['password']; // User's password input from the form

    $sql = "SELECT * FROM users WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $storedPassword = $row['Password'];
        $userType = $row['usertype'];
        $userId = $row['Id'];  

        if ($inputPassword === $storedPassword) {
            if ($userType == 'admin') {
                $_SESSION['username'] = $username;
                $_SESSION['usertype'] = $userType;
                logEvent($conn, $userId, 'Login Success', 'Admin logged in successfully');
                header('Location: admin.php');
                exit();
            } else {
                logEvent($conn, $userId, 'Login Failure', 'Non-admin tried to access admin');
                echo "<script>alert('Access Denied: You are not an admin.');</script>";
            }
        } else {
            logEvent($conn, $userId, 'Login Failure', 'Invalid password attempted');
            echo "<script>alert('Invalid Password');</script>";
        }
    } else {
        logEvent($conn, null, 'Login Failure', 'Invalid username attempted');
        echo "<script>alert('Incorrect Username');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="assets/login.css">
</head>

<body>
    <div class="forms">
        <img src="img/ticketblue.png" alt="">
        <h2>Login</h2>
        <form action="" method="post">
            <label>Username</label>
            <input type="text" name="username" placeholder="Username" required>
            <label>password</label>
            <input type="password" name="password" placeholder="Password" required>
            <button name="submit"><span>Login</span></button>
        </form>
    </div>

</body>

</html>