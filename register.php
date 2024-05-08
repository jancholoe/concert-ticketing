<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Register</title>
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
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <?php
            include("php/config.php");
            if (isset($_POST['submit'])) {
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
            } else {
            ?>
                <header>Sign Up</header>
                <form action="" method="post">
                    <div class="field input">
                        <label for="firstName">First Name</label>
                        <input type="text" name="firstName" id="firstName" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="lastName">Last Name</label>
                        <input type="text" name="lastName" id="lastName" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="age">Age</label>
                        <input type="number" name="age" id="age" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" autocomplete="off" required onkeyup="validatePassword();">
                        <span id="password-message"></span>
                    </div>

                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Register">
                    </div>

                    <div class="links">
                        Already a member? <a href="index.php">Sign In</a>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
</body>

</html>