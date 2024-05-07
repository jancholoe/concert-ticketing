<?php 
   session_start();
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
              if(isset($_POST['submit'])){
                // Verify reCAPTCHA
                $recaptchaResponse = $_POST['g-recaptcha-response'];
                $secretKey = '6Le_c9IpAAAAAH9Xu3S3oVmW2Jf-a6G-UCdaRY6B'; 
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = array(
                    'secret' => $secretKey,
                    'response' => $recaptchaResponse
                );

                $options = array(
                    'http' => array (
                        'method' => 'POST',
                        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                        'content' => http_build_query($data)
                    )
                );

                $context  = stream_context_create($options);
                $verify = file_get_contents($url, false, $context);

                if ($verify === FALSE) {
                    // Handle error
                    die("Failed to fetch reCAPTCHA response");
                }

                $captcha_success = json_decode($verify);

                if ($captcha_success->success==false) {
                    // If reCAPTCHA verification fails
                    echo "<div class='message'>
                      <p>Please confirm that you're not a robot.</p>
                       </div> <br>";
                    echo "<a href='index.php'><button class='btn'>Go Back</button>";
                } else if ($captcha_success->success==true) {
                    // If reCAPTCHA verification succeeds
                    $email = mysqli_real_escape_string($con,$_POST['email']);
                    $password = mysqli_real_escape_string($con,$_POST['password']);

                    $result = mysqli_query($con,"SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Select Error");
                    $row = mysqli_fetch_assoc($result);

                    if(is_array($row) && !empty($row)){
                        $_SESSION['valid'] = $row['Email'];
                        $_SESSION['username'] = $row['Username'];
                        $_SESSION['age'] = $row['Age'];
                        $_SESSION['id'] = $row['Id'];
                        header("Location: customerpage copy.php");
                    } else {
                        echo "<div class='message'>
                            <p>Wrong Username or Password</p>
                        </div> <br>";
                        echo "<a href='index.php'><button class='btn'>Go Back</button>";
                    }
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
