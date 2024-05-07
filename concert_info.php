<?php
include 'connection.php';

// Check if concert_ID is set in the URL
if (isset($_GET['id'])) {
    // Retrieve the concert_ID from the URL
    $id = $_GET['id'];

    /* Retrieve concert information */
    $sql = "SELECT * FROM concerts WHERE concert_ID = ?";
    

    $stmt = mysqli_prepare($conn, $sql);
    

    mysqli_stmt_bind_param($stmt, "i", $id);


    mysqli_stmt_execute($stmt);


    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/customer/css/info.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
</head>
<body style="background-color:#6e5a11;">
    <div class="booking-panel">
        <div class="booking-panel-section booking-panel-section1">
            <h1>CONCERT INFO</h1>
        </div>
        <div class="booking-panel-section booking-panel-section2" onclick="window.history.go(-1); return false;">
            <i class="fas fa-2x fa-times"></i>
        </div>
        <div class="booking-panel-section booking-panel-section3">
            <div class="movie-box">
                <?php
                echo '<img src="img/uploads/' . $row['banner'] . '" alt="">';
                ?>
            </div>
        </div>
        <div class="booking-panel-section booking-panel-section4">
            <div class="title"><?php echo $row['concert_name']; ?></div>
            <div class="movie-information">
                <table>
                    <tr>
                        <td>Description</td>
                        <td><?php echo $row['description']; ?></td>
                    </tr>
                    <tr>
                        <td>Schedule</td>
                        <td><?php echo $row['schedule']; ?></td>
                    </tr>
                </table>
            </div>
            <hr>
        </div>
    </div>
    
</body>
</html>