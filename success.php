<?php
include 'connection.php';
require_once('tcpdf/tcpdf.php');
// Get the customer's name from the URL parameter
$customerName = isset($_GET['name']) ? urldecode($_GET['name']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
    <style>
        .font > i {
            font-size: 30px;
            color: white;
            height: 60px;
            width: 60px;
            border-radius: 50%;
            line-height: 60px;
            transition: all 0.5s ease;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <center>
        <div class="font">
            <i class="fas fa-check-circle" style="background-color: #3cbb6c;"></i>
        </div>
        <h3 style="text-align: center;">Hi! <?php echo htmlspecialchars($customerName); ?>, your ticket information has been sent to your email! Thank you for purchasing.</h3>
        <!--<a href="generate_pdf.php?name=<?php echo urlencode($customerName); ?>" target="_blank" class="download-link">Click here if you want to download your ticket</a>-->
        <br>
        <a href="customerpage copy.php">Back to the main page</a>
    </center>
</body>
</html>
