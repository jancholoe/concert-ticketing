<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login_process.php" method="post">
            <label for="login_username">Username:</label>
            <input type="text" id="login_username" name="login_username" required>
            <label for="login_password">Password:</label>
            <input type="password" id="login_password" name="login_password" required>
            <input type="submit" value="Login">
        </form>

        <h2>Sign Up</h2>
        <form action="signup_process.php" method="post">
            <label for="signup_username">Username:</label>
            <input type="text" id="signup_username" name="signup_username" required>
            <label for="signup_email">Email:</label>
            <input type="email" id="signup_email" name="signup_email" required>
            <label for="signup_password">Password:</label>
            <input type="password" id="signup_password" name="signup_password" required>
            <input type="submit" value="Sign Up">
        </form>
    </div>
</body>
</html>
