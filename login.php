<?php 
include'connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = '$username'  ";

    $result = mysqli_query($conn, $sql);
    

    if(mysqli_num_rows($result) > 0){
        if(!password_verify($password, $hash)){
            echo " 'login.php', 'Invalid Password' ";
        }
        $row = mysqli_fetch_array($result);
        header('Location: admin.php');
    }else{
        echo"<script> alert('Incorrect Username'); </script>";
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

<!-- 
    <div class="forms">
            <h1>Login Page</h1>
            <div class="form-group">
                <input type="text" name="username" placeholder="Enter your username:" required> 
            </div>
            <div class="form-group">
                <input type="text" name="username" placeholder="Enter your password:" required> 
            </div>
            <div class="form-group">
				<input type="submit" name="submit" value="Submit">
			</div>
        </div>
-->
</body>
</html>