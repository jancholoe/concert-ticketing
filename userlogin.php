<?php
include("php/config.php");
session_start();
if (isset($_SESSION['valid'])) {
    header('Location: customerpage copy.php');
    exit;
}
function logEvent($conn, $userId, $eventType, $description)
{
    $sql = "INSERT INTO logs (user_id, event_type, description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $userId, $eventType, $description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
$allowCaptchaBypass = true;

if (isset($_POST['login'])) {
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
}
if (isset($_POST['register'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $password = $_POST['password'];

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/", $password)) {
        echo "<div class='message'><p>Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one number, and one special character.</p></div>";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $userType = 'user';
        $verify_query = mysqli_query($con, "SELECT Email FROM users WHERE Email='$email'");

        if (mysqli_num_rows($verify_query) > 0) {
            echo "<div class='message'>
                              <p>This email is used, Try another One Please!</p>
                          </div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
        } else {
            $query = "INSERT INTO users(FirstName, LastName, Username, Email, Age, Password, UserType) 
                                  VALUES('$firstName', '$lastName', '$username', '$email', '$age', '$password', '$userType')";
            mysqli_query($con, $query) or die("Error Occurred");
            echo "<div class='message'>
                              <p>Registration successfully!</p>
                          </div> <br>";
            echo "<a href='index.php'><button class='btn'>Login Now</button>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in & Sign up Form</title>
    <link rel="stylesheet" href="style/userlogin.css" />
 
</head>

<body>
    <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <form method="post" autocomplete="off" class="sign-in-form">
                        <div class="logo">
                            <img src="./img/logo.png" alt="easyclass" />
                            <h4>KRK - Ticketing System</h4>
                        </div>

                        <div class="heading">
                            <h2>Welcome Back</h2>
                            <h6>Not registred yet?</h6>
                            <a href="#" class="toggle">Sign up</a>
                        </div>
                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" name="email" id="email" class="input-field" autocomplete="off" required />
                                <label>Email</label>
                            </div>

                            <div class="input-wrap">
                                <input type="password" name="password" class="input-field" autocomplete="off" required />
                                <label>Password</label>
                            </div>

                            <div class="g-recaptcha" data-sitekey="6Le_c9IpAAAAAJfnwn6qtbVWa6ACZNulRkRXy8ei "></div>


                            <input type="submit" name="login" value="Sign In" class="sign-btn" />

                            <p class="text">
                                Forgotten your password or you login datails?
                                <a href="#">Get help</a> signing in
                            </p>
                        </div>
                    </form>
                    <form method="post" autocomplete="off" class="sign-up-form">
                        <div class="logo">
                            <img src="./img/logo.png" alt="easyclass" />
                            <h4>KRK- Ticketing System</h4>
                        </div>

                        <div class="heading">
                            <h2>Get Started</h2>
                            <h6>Already have an account?</h6>
                            <a href="#" class="toggle">Sign in</a>
                        </div>

                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" name="firstName" minlength="4" class="input-field" autocomplete="off" required />
                                <label>First name</label>
                            </div>
                            <div class="input-wrap">
                                <input type="text" name="lastName" minlength="4" class="input-field" autocomplete="off" required />
                                <label>Last name</label>
                            </div>
                            <div class="input-wrap">
                                <input type="text" name="username" minlength="4" class="input-field" autocomplete="off" required />
                                <label>Username</label>
                            </div>
                            <div class="input-wrap">
                                <input type="text" name="age" minlength="4" class="input-field" autocomplete="off" required />
                                <label>Age</label>
                            </div>
                            <div class="input-wrap">
                                <input type="email" name="email" class="input-field" autocomplete="off" required />
                                <label>Email</label>
                            </div>

                            <div class="input-wrap">
                                <input type="password" name="password" minlength="4" class="input-field" autocomplete="off" required />
                                <span id="password-message"></span>
                                <label>Password</label>
                            </div>

                            <input type="submit" name="register" value="Sign Up" class="sign-btn" />

                            <p class="text">
                                By signing up, I agree to the
                                <a href="#">Terms of Services</a> and
                                <a href="#">Privacy Policy</a>
                            </p>
                        </div>
                    </form>
                </div>

                <div class="carousel">
                    <div class="images-wrapper">
                        <img src="./img/image1.png" class="image img-1 show" alt="" />
                        <img src="./img/image2.png" class="image img-2" alt="" />
                        <img src="./img/image3.png" class="image img-3" alt="" />
                    </div>

                    <div class="text-slider">
                        <div class="text-wrap">
                            <div class="text-group">
                                <h2>Buy Tickets Online!</h2>
                                <h2>Be a member!</h2>
                                <h2>Enjoy concert!</h2>
                            </div>
                        </div>

                        <div class="bullets">
                            <span class="active" data-value="1"></span>
                            <span data-value="2"></span>
                            <span data-value="3"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Javascript file -->

    <script src="js/userlogin.js"></script>
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var message = document.getElementById("password-message");
            var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})");

            if (strongRegex.test(password)) {
                message.style.color = "green";
                message.innerHTML = "Strong password";
            } else {
                message.style.color = "red";
                message.innerHTML = "Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character (!@#$%^&*)";
            }
        }
    </script>
</body>

</html>