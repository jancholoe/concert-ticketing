<?php
include 'connection.php';

if (isset($_GET['concert_ID'])) {
    $concert_ID = $_GET['concert_ID'];

    $sql = "SELECT cd.*, cp.*
            FROM concert_details cd
            INNER JOIN concert_price cp ON cd.concert_ID = cp.price_ID
            WHERE cd.concert_ID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $concert_ID);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $concert = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
} else {
    header('Location: ManageEvent.php');
    exit();
}

if (isset($_POST['Update_Concert'])) {
    $updated_concert_name = $_POST['concert_name'];
    $updated_schedule = $_POST['schedule'];
    $updated_time = $_POST['time'];
    $updated_description = $_POST['description'];
    $updated_vipSeat = $_POST['vipSeat'];
    $updated_lower_boxSeat = $_POST['lower_boxSeat'];
    $updated_upper_boxSeat = $_POST['upper_boxSeat'];
    $updated_gen_addSeat = $_POST['gen_addSeat'];
    $updated_vip = $_POST['vip'];
    $updated_lower_box = $_POST['lower_box'];
    $updated_upper_box = $_POST['upper_box'];
    $updated_gen_add = $_POST['gen_add'];

    $update_concert_sql = "UPDATE concert_details SET
                            concert_name = ?,
                            schedule = ?,
                            time = ?,
                            description = ?
                            WHERE concert_ID = ?";
    $update_concert_stmt = mysqli_prepare($conn, $update_concert_sql);
    mysqli_stmt_bind_param($update_concert_stmt, "ssssi", $updated_concert_name, $updated_schedule, $updated_time, $updated_description, $concert_ID);
    mysqli_stmt_execute($update_concert_stmt);
    mysqli_stmt_close($update_concert_stmt);

    $update_price_sql = "UPDATE concert_price SET
                            vipSeat = ?,
                            lower_boxSeat = ?,
                            upper_boxSeat = ?,
                            gen_addSeat = ?,
                            vip = ?,
                            lower_box = ?,
                            upper_box = ?,
                            gen_add = ?
                            WHERE price_ID = ?";
    $update_price_stmt = mysqli_prepare($conn, $update_price_sql);
    mysqli_stmt_bind_param($update_price_stmt, "iiiiiiiii", $updated_vipSeat, $updated_lower_boxSeat, $updated_upper_boxSeat, $updated_gen_addSeat, $updated_vip, $updated_lower_box, $updated_upper_box, $updated_gen_add, $concert_ID);
    mysqli_stmt_execute($update_price_stmt);
    mysqli_stmt_close($update_price_stmt);

    header('Location: ManageEvent.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
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
            <h2>Update Concert Info</h2>
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
                <input type="text" id="form" name="concert_name" value="<?php echo isset($concert['concert_name']) ? $concert['concert_name'] : ''; ?>" required>
            </div>
            <div class="form-group">   
                <label for="input">Date:</label>
                <input type="date" id="form" name="schedule" value="<?php echo isset($concert['schedule']) ? $concert['schedule'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="input">Time:</label>
                <input type="time" id="form" name="time" value="<?php echo isset($concert['time']) ? $concert['time'] : ''; ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="input">POSTER:</label>
            <input type="file" id="form" accept="image/png, image/jpeg, image/jpg" name="poster" class="box">
            <!-- Add a condition to display the current poster if available -->
            <?php if (isset($concert['poster'])): ?>
                <img src="path/to/your/uploads/<?php echo $concert['poster']; ?>" alt="Current Poster" width="100">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="input">DESCRIPTION:</label>
            <textarea name="description" id="form" cols="30" rows="10" required><?php echo isset($concert['description']) ? $concert['description'] : ''; ?></textarea>
        </div>

        <h2>SEATS</h2>

        <div class="form-group-group">
            <div class="form-group">
                <label for="input">VIP:</label>
                <input type="number" id="form" name="vipSeat" value="<?php echo isset($concert['vipSeat']) ? $concert['vipSeat'] : ''; ?>" required>
            </div>
            <div class="form-group">   
                <label for="input">LOWER BOX:</label>
                <input type="number" id="form" name="lower_boxSeat" value="<?php echo isset($concert['lower_boxSeat']) ? $concert['lower_boxSeat'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="input">UPPER BOX:</label>
                <input type="number" id="form" name="upper_boxSeat" value="<?php echo isset($concert['upper_boxSeat']) ? $concert['upper_boxSeat'] : ''; ?>" required>
            </div>
            <div class="form-group">   
                <label for="input">GENERAL ADMISSION:</label>
                <input type="number" id="form" name="gen_addSeat" value="<?php echo isset($concert['gen_addSeat']) ? $concert['gen_addSeat'] : ''; ?>" required>
            </div>
        </div>

        <div class="form-group-group">
            <div class="form-group">
                <label for="input">VIP Price:</label>
                <input type="number" id="form" name="vip" value="<?php echo isset($concert['vip']) ? $concert['vip'] : ''; ?>" required>
            </div>
            <div class="form-group">   
                <label for="input">LOWER BOX Price:</label>
                <input type="number" id="form" name="lower_box" value="<?php echo isset($concert['lower_box']) ? $concert['lower_box'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="input">UPPER BOX Price:</label>
                <input type="number" id="form" name="upper_box" value="<?php echo isset($concert['upper_box']) ? $concert['upper_box'] : ''; ?>" required>
            </div>
            <div class="form-group">   
                <label for="input">GENERAL AD Price:</label>
                <input type="number" id="form" name="gen_add" value="<?php echo isset($concert['gen_add']) ? $concert['gen_add'] : ''; ?>" required>
            </div>
        </div>



        <div class="form-button">
            <button type="text" class="submit" name="Update_Concert">Update Event</button>
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