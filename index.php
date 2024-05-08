<?php
session_start();
if (isset($_SESSION['valid'])) {
    header('Location: customerpage copy.php'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Customer Login Page</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <?php
            include("php/config.php");

            function logEvent($conn, $userId, $eventType, $description)
            {
                $sql = "INSERT INTO logs (user_id, event_type, description) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "iss", $userId, $eventType, $description);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            $allowCaptchaBypass = true;

            if (isset($_POST['submit'])) {
                $recaptchaResponse = $_POST['g-recaptcha-response'];
                $secretKey = '6Le_c9IpAAAAAH9Xu3S3oVmW2Jf-a6G-UCdaRY6B';
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = array(
                    'secret' => $secretKey,
                    'response' => $recaptchaResponse
                );

                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                        'content' => http_build_query($data)
                    )
                );

                $context = stream_context_create($options);
                $verify = @file_get_contents($url, false, $context);
                $captcha_success = $verify ? json_decode($verify) : null;

                if ($captcha_success === null && $allowCaptchaBypass) {
                    logEvent($con, null, 'Login Notice', 'CAPTCHA service unavailable, bypassing CAPTCHA');
                    $bypassCaptcha = true;
                } elseif ($captcha_success && $captcha_success->success == false) {
                    echo "<div class='message'><p>Please confirm that you're not a robot.</p></div><br>";
                    echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
                    logEvent($con, null, 'Login Failed', 'Failed reCAPTCHA verification');
                    $bypassCaptcha = false;
                } else {
                    $bypassCaptcha = true;
                }

                if ($bypassCaptcha) {
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $password = mysqli_real_escape_string($con, $_POST['password']);
                    $query = "SELECT * FROM users WHERE Email = ?";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                        if (password_verify($password, $row['Password'])) {
                            $_SESSION['valid'] = $row['Email'];
                            $_SESSION['username'] = $row['Username'];
                            $_SESSION['age'] = $row['Age'];
                            $_SESSION['id'] = $row['Id'];
                            logEvent($con, $row['Id'], 'Login Success', 'User logged in successfully');
                            header("Location: customerpage copy.php"); // Ensure this is the correct target page
                            exit;
                        } else {
                            echo "<div class='message'><p>Wrong Username or Password</p></div><br>";
                            echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
                            logEvent($con, null, 'Login Failed', 'Incorrect username or password');
                        }
                    } else {
                        echo "<div class='message'><p>No user found with that email address.</p></div><br>";
                        echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
                        logEvent($con, null, 'Login Failed', 'No user found with that email');
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
            ?>
                <header>Login</header>
                <form action="" method="post">
                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" required>
                    </div>
                    <div class="field input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" required>
                    </div>

                    <div class="g-recaptcha" data-sitekey="6Le_c9IpAAAAAJfnwn6qtbVWa6ACZNulRkRXy8ei "></div>

                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Login" required>
                    </div>
                    <div class="links">
                        Don't have an account? <a href="register.php">Sign Up Now</a>
                    </div>
                </form>
        </div>
    <?php } ?>
    </div>
</body>

</html>