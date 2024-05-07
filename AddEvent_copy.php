<?php 
include 'connection.php';

/*add concerts
if (isset($_POST['Add_Event'])){
    $concert_name = $_POST['concert_name'];
    $price = $_POST['price'];
    $seat = $_POST['seat'];
    $description = $_POST['description'];
    $schedule = $_POST['schedule'];
    $banner = "";
    $banner_tmp_name = "";
    $banner_folder = "";

    if (isset($_FILES['banner']) && !empty($_FILES['banner']['name'])) {
        $banner = $_FILES['banner']['name'];
        $banner_tmp_name = $_FILES['banner']['tmp_name'];
        $banner_folder = 'img/uploads/' . $banner;
    }

    if (empty($concert_name) || empty($price) || empty($seat) || empty($description) || empty($schedule) || empty($banner)){
        $message[] = 'Please fill out all fields.';
    } else {
        $sql = "INSERT into concerts(concert_name, price, seat, description, schedule, banner) values ('$concert_name', '$price', '$seat', '$description', '$schedule', '$banner')";
        $upload = mysqli_query($conn, $sql);

        if ($upload){
            move_uploaded_file($banner_tmp_name, $banner_folder);
            $message[] = 'Concert added successfully.';
        } else {
            $message[] = 'Error adding concert: ' . mysqli_error($conn);
        }
    }
};
*/

/* Add transaction 
if (isset($_POST['Add_Concert'])) {
    // concert_details
    $concert_name = $_POST['concert_name'];
    $schedule = $_POST['schedule'];
    $time = $_POST['time'];
    $description = $_POST['description'];
    $poster = "";
    $poster_tmp_name = "";
    $poster_folder = "";

    if (isset($_FILES['poster']) && !empty($_FILES['poster']['name'])) {
        $poster = $_FILES['poster']['name'];
        $poster_tmp_name = $_FILES['poster']['tmp_name'];
        $poster_folder = 'img/uploads_copy/' . $poster;
    }

    // concert_price
    $vip = $_POST['vip'];
    $lower_box = $_POST['lower_box'];
    $upper_box = $_POST['upper_box'];
    $gen_add = $_POST['gen_add'];
    $vipSeat = $_POST['vipSeat'];
    $lower_boxSeat = $_POST['lower_boxSeat'];
    $upper_boxSeat = $_POST['upper_boxSeat'];
    $gen_addSeat = $_POST['gen_addSeat'];

    // SQL query for concert_details
    $sqlDetails = "INSERT INTO concert_details 
    (concert_name, schedule, time, description, poster) 
    VALUES 
    ('$concert_name', '$schedule', '$time', '$description', '$poster')";

    // SQL query for concert_price
    $sqlPrice = "INSERT INTO concert_price 
    (vip, lower_box, upper_box, gen_add, vipSeat, lower_boxSeat, upper_boxSeat, gen_addSeat) 
    VALUES 
    ('$vip', '$lower_box', '$upper_box', '$gen_add', '$vipSeat', '$lower_boxSeat', '$upper_boxSeat', '$gen_addSeat')";

echo "SQL Details: $sqlDetails<br>";
echo "SQL Price: $sqlPrice<br>";
    // Execute both queries
    if ($conn->query($sqlDetails) && $conn->query($sqlPrice)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }else {
        // Output any error messages
        echo "Error: " . $conn->error;
    }
}
*/

if (isset($_POST['Add_Concert'])) {
    // concert_details
    $concert_name = $_POST['concert_name'];
    $schedule = $_POST['schedule'];
    $time = $_POST['time'];
    $description = $_POST['description'];
    $poster = "";
    $poster_tmp_name = "";
    $poster_folder = "";

    if (isset($_FILES['poster']) && !empty($_FILES['poster']['name'])) {
        $poster = $_FILES['poster']['name'];
        $poster_tmp_name = $_FILES['poster']['tmp_name'];
        $poster_folder = 'img/uploads_copy/' . $poster;

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($poster_folder, PATHINFO_EXTENSION));
        $allowedFormats = ['jpg', 'jpeg', 'png'];
        if (!in_array($imageFileType, $allowedFormats)) {
            die("Error: Only JPG, JPEG, and PNG files are allowed.");
        }

        // Check if the file already exists
        if (file_exists($poster_folder)) {
            die("Error: File already exists.");
        }

        // Move the uploaded file to the desired folder
        if (!move_uploaded_file($poster_tmp_name, $poster_folder)) {
            die("Error: File upload failed.");
        }
    }

    // concert_price
    $vip = $_POST['vip'];
    $lower_box = $_POST['lower_box'];
    $upper_box = $_POST['upper_box'];
    $gen_add = $_POST['gen_add'];
    $vipSeat = $_POST['vipSeat'];
    $lower_boxSeat = $_POST['lower_boxSeat'];
    $upper_boxSeat = $_POST['upper_boxSeat'];
    $gen_addSeat = $_POST['gen_addSeat'];

    // SQL query for concert_details
    $sqlDetails = "INSERT INTO concert_details 
    (concert_name, schedule, time, description, poster) 
    VALUES 
    ('$concert_name', '$schedule', '$time', '$description', '$poster')";

    // Execute the query
    if (!$conn->query($sqlDetails)) {
        die("Error: " . $conn->error);
    }

    // Get the last inserted ID from concert_details
    $concertDetailsID = $conn->insert_id;

    // SQL query for concert_price
    $sqlPrice = "INSERT INTO concert_price 
    (vip, lower_box, upper_box, gen_add, vipSeat, lower_boxSeat, upper_boxSeat, gen_addSeat) 
    VALUES 
    ('$vip', '$lower_box', '$upper_box', '$gen_add', '$vipSeat', '$lower_boxSeat', '$upper_boxSeat', '$gen_addSeat')";

    // Execute the query
    if (!$conn->query($sqlPrice)) {
        die("Error: " . $conn->error);
    }

    // Get the last inserted ID from concert_price
    $concertPriceID = $conn->insert_id;

    // Insert data into sales table
    $sqlSales = "INSERT INTO sales (price_ID, vipSeatS, lower_boxSeatS, upper_boxSeatS, gen_addSeatS) VALUES ('$concertPriceID', '$vipSeat', '$lower_boxSeat', '$upper_boxSeat', '$gen_addSeat')";

    // Execute the query
    if (!$conn->query($sqlSales)) {
        die("Error: " . $conn->error);
    }

    // Redirect after successful insertion
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
} else {
    // Output any error messages
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/admin/css/add_event_copy.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
    <link rel="stylesheet" href="assets/admin/css/jquery.datetimepicker.min.css">
</head>
<body>  
    <!-- sidebar --->
    <div class="sidebar">
    <!--<div class="logo"></div>-->
    <ul class="menu">
    <li class="logo"><a href="#">
        <i class="fas fa-ticket-alt"></i>
        <span>KRK Tickets</span>
        </a>
    </li>
    <li>
        <a href="admin.php">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
        </a>
    </li>
    <li><a href="manage_users.php">
        <i class="fas fa-user"></i>
        <span>Users</span>
        </a>
    </li>
    <li class="active">
        <a href="ManageEvent.php">
        <i class="fas fa-calendar-plus"></i>
        <span>Events</span>
        </a>
    </li>
    <li><a href="reports.php">
        <i class="fas fa-receipt"></i>
        <span>Reports</span>
        </a>
    </li>
    <li class="">
        <a href="message.php">
        <i class="fas fa-envelope"></i>
        <span>Message</span>
        </a>
    </li> 
    <li><a href="login.php" class="logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
        </a>
    </li>  
    </ul>
</div>


<div class="main-content">
    <div class="header-wrapper">
        <div class="header-title">
            <span></span>
            <h2>Add New Concert</h2>
        </div>
        <!--
        <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search" />
        </div>
-->
</div>
        <div class="events">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group-group">
                <div class="form-group">
                    <label for="input">Concert Name:</label>
                    <input type="text" id="form" name="concert_name" placeholder="Enter Event Name:" required>
                </div>
                <div class="form-group">   
                    <label for="input">Date:</label>
                    <input type="date" id="form" name="schedule" placeholder="Enter Price:" required>
                </div>
                <div class="form-group">
                    <label for="input">Time:</label>
                    <input type="time" id="form" name="time" placeholder="Add Seat:" required>
                </div>
                </div>

                <div class="form-group">
                <label for="input">POSTER:</label>
                <input type="file" id="form" accept="image/png, image/jpeg, image/jpg" name="poster" class="box" required>
                </div>
                <div class="form-group">
                <label for="input">DESCRIPTION:</label>
                    <textarea name="description" id="form" cols="30" rows="10" placeholder="Type here..." required></textarea>
                </div>

                <h2>SEATS</h2>
                <div class="form-group-group">
                <div class="form-group">
                    <label for="input">VIP:</label>
                    <input type="number" id="form" name="vipSeat" placeholder="Enter Seats:" required>
                </div>
                <div class="form-group">   
                    <label for="input">LOWER BOX:</label>
                    <input type="number" id="form" name="lower_boxSeat" placeholder="Enter Seats:" required>
                </div>
                <div class="form-group">
                    <label for="input">UPPER BOX:</label>
                    <input type="number" id="form" name="upper_boxSeat" placeholder="Enter Seats:" required>
                </div>
                <div class="form-group">   
                    <label for="input">GENERAL ADMISSION:</label>
                    <input type="number" id="form" name="gen_addSeat" placeholder="Enter Seats:" required>
                </div>
                </div>
                <div class="form-group-group">
                    <div class="form-group">
                    <input type="number" id="form" name="vip" placeholder="Enter Price:" required>
                </div>
                <div class="form-group">   
                    <input type="number" id="form" name="lower_box" placeholder="Enter Price:" required>
                </div>
                <div class="form-group">
                    <input type="number" id="form" name="upper_box" placeholder="Enter Price:" required>
                </div>
                <div class="form-group">   
                    <input type="number" id="form" name="gen_add" placeholder="Enter Price:" required>
                </div>
                </div>
                <div class="form-button">
                    <button type="text" class="submit" name="Add_Concert">Add Event</button>
                </div>
            </form>
        </div>
</div>
<script src="assets/admin/js/jquery.js"></script>
<script src="assets/admin/js/jquery.datetimepicker.full.min.js"></script>
<script>
		$("#timedatePicker").datetimepicker({
			/*step:15*/
		});
</script>
</body>
</html>