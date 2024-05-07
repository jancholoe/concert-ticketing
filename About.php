<?php
include 'connection.php';

// Check if the form is submitted
if (isset($_POST['Add_Concert'])) {
    // Retrieve form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert data into the message table
    $insertQuery = "INSERT INTO `message` (`firstname`, `lastname`, `email`, `MSG`) 
                    VALUES ('$firstname', '$lastname', '$email', '$message')";

    // Execute the query
    $result = mysqli_query($conn, $insertQuery);

    // Check if the query was successful
    if ($result) {
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concert tickets</title>
    <link rel="stylesheet" href="assets/customer/css/about.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
</head>
<body>
    <header>
        <a href="#" class="logo">KRK Tickets</a>
        <div class="group">
            <ul class="navigation">
                <li><a href="customerpage copy.php">HOME</a></li>
                <li><a href="#">CONTACT US</a></li>
            </ul>
            <div class="search">
                <span class="icon">
                <i class="fas fa-search searchBtn"></i>
                <i class="fas fa-times closeBtn"></i>
                </span>
            </div>

        </div>
            <div class="searchBox">
                <input type="text" placeholder="Search Concerts...">
            </div>
    </header>
    <div class="content">
        <div class="contact">
            <h2>Contact us</h2>
            <hr style="margin-bottom: 10px;">
            <h3>Send us message</h3>
            <form method="post" action="">
            <div class="form-group">
            <input type="text" id="form" name="firstname" placeholder="First Name:" required>
            </div>
            <div class="form-group">
            <input type="text" id="form" name="lastname" placeholder="Last Name:" required>
            </div>
            <div class="form-group">
            <input type="text" id="form" name="email" placeholder="Email Address:" required>
            </div>
            <div class="form-group">
            <textarea name="message" id="form" cols="30" rows="10" placeholder="Message here..." required></textarea>
            <div class="form-button">
                    <button type="submit" class="submit" name="Add_Concert">Send message</button>
            </div>
            </div>
            </form>
        </div>
        <div class="about">
            <h2>About us</h2>
            <hr>
            <p style="letter-spacing: 3px;">The concert ticketing system is an online platform using a customized website 
                that enables customers to purchase concert tickets conveniently. It can be access
                using desktops and laptops. The platform focuses on selling and buying tickets, its 
                allowing event organizer to manage and create concert events easily also given a customer 
                a convenient ticketing purchase process 
            </p>
    </div>
    <!--para sa search-->
    <script>
        let searchBtn = document.querySelector('.searchBtn');
        let closeBtn = document.querySelector('.closeBtn');
        let searchBox = document.querySelector('.searchBox')
        searchBtn.onclick = function(){
            searchBox.classList.add('active');
            closeBtn.classList.add('active');
            searchBtn.classList.add('active');
        }
        closeBtn.onclick = function(){
            searchBox.classList.remove('active');
            closeBtn.classList.remove('active');
            searchBtn.classList.remove('active');
        }
    </script>
</body>
</html>