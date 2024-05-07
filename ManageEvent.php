<?php 
include'connection.php';

//retrieve
$select = "SELECT 
    concert_details.*, 
    concert_price.*
FROM 
    concert_details
JOIN 
    concert_price ON concert_details.concert_ID = concert_price.price_ID
ORDER BY 
    date_created AND time ASC";


$result = mysqli_query($conn, $select);
if (!$result) {
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

//delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['concert_ID'])) {
    $concert_IDToDelete = $_GET['concert_ID'];

    mysqli_begin_transaction($conn);

    try {
        // Step 1: Delete from concert_details
        $deleteConcertDetailsSql = "DELETE FROM concert_details WHERE concert_ID = ?";
        $deleteConcertDetailsStmt = mysqli_prepare($conn, $deleteConcertDetailsSql);
        mysqli_stmt_bind_param($deleteConcertDetailsStmt, "i", $concert_IDToDelete);
        mysqli_stmt_execute($deleteConcertDetailsStmt);

        // Step 2: Delete from concert_price
        $deleteConcertPriceSql = "DELETE FROM concert_price WHERE price_ID = ?";
        $deleteConcertPriceStmt = mysqli_prepare($conn, $deleteConcertPriceSql);
        mysqli_stmt_bind_param($deleteConcertPriceStmt, "i", $concert_IDToDelete);
        mysqli_stmt_execute($deleteConcertPriceStmt);

        // Step 3: Delete from sales
        $deleteSalesSql = "DELETE FROM sales WHERE sales_ID = ?";
        $deleteSalesStmt = mysqli_prepare($conn, $deleteSalesSql);
        mysqli_stmt_bind_param($deleteSalesStmt, "i", $concert_IDToDelete);
        mysqli_stmt_execute($deleteSalesStmt);

        // Commit the transaction
        mysqli_commit($conn);

        // Redirect to refresh the page after successful deletion
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        mysqli_rollback($conn);
        die("Database delete failed. Error: " . $e->getMessage());
    } finally {
        // Close prepared statements
        mysqli_stmt_close($deleteConcertDetailsStmt);
        mysqli_stmt_close($deleteConcertPriceStmt);
        mysqli_stmt_close($deleteSalesStmt);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event</title>
    <link rel="stylesheet" href="assets/admin/css/manage_event.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- dataTables -->
    <link rel="stylesheet" href="assets/jTable.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
th{
    background-color: #15172b;
    color: #fff;
    height: 40px;
}
td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #747581;
}
.text-center{
    text-align: center;
}

</style>
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
        <a href="#">
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

     <!-- upper --->
     <div class="main-content">
    <div class="header-wrapper">
        <div class="header-title">
        <h2>List of concert</h2>
        </div>
        <div class="add">
            
        </div>
        <!--
        <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search" />
        </div>
-->
</div>
<!-- Main --->
<div class="list-wrapper">
<div class="list">
    <div class="cardHeader">
        <a href="AddEvent_copy.php" class="add-new"><i class="fas fa-plus"></i> Add New</a>
    </div>
    <table id="myTable" class="display">
    <colgroup>
    <col width="25%">
    <col width="10%">
    <col width="10%">
    <col width="10%">
    <col width="30%">
    <col width="15%">
</colgroup>
        <thead>
                <tr>
                    <th>Concert Name</th>
                    <th>Seats</th>
                    <th>Seats Available</th>
                    <th>Price</th>
                    <th>Schedule</th>
                    <th>Action</th>              
                </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td><?php echo $row['concert_name']; ?></td>
                    <td>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">VIP SEAT:</span><br>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">LOWER BOX:</span><br>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">UPPER BOX:</span><br>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">GEN ADD:</span><br>
                    </td>
                    <td class="text-center">
                        <?php echo $row['vipSeat']; ?><br>
                        <?php echo $row['lower_boxSeat']; ?><br>
                        <?php echo $row['upper_boxSeat']; ?><br>
                        <?php echo $row['gen_addSeat']; ?><br>
                    </td>
                    <td>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;"> ₱</span><?php echo number_format( $row['vip']); ?><br>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;"> ₱</span><?php echo number_format( $row['lower_box']); ?><br>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;"> ₱</span><?php echo number_format( $row['upper_box']); ?><br>
                        <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;"> ₱</span><?php echo number_format( $row['gen_add']); ?><br>
                    </td>
                    <td class="text-center">
                    <?php 
                        $scheduleTime = new DateTime($row['schedule'] . ' ' . $row['time']);
                        echo $scheduleTime->format('M d, Y - g:i A'); 
                    ?>
                    </td>
                    <td>
                    <div class="action-dropdown">
                    <button class="action-btn">Action <i class="fas fa-caret-down"></i></button>
                        <div class="dropdown-content">
                            <a class="edit-btn" href="updateEvent.php?concert_ID=<?php echo $row['concert_ID']; ?>">Edit</a>
                            <a class="delete-btn" href="#" onclick="deleteConcert(<?php echo $row['concert_ID']; ?>)">Delete</a>
                        </div>
                    </div>
                    </td>
                </tr>
                  <?php } 
                    ?>
        </tbody>
    </table>
</div>
</div>
</div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
    <!-- Action script(para sa dropdown)-->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    var actionButtons = document.querySelectorAll('.action-btn');

    actionButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            var dropdownContent = event.currentTarget.nextElementSibling;

            // Close other dropdowns
            closeOtherDropdowns(button);

            // Toggle the 'show' class on the action-dropdown
            dropdownContent.classList.toggle('show');
        });
    });

    // Close dropdowns if the user clicks outside
    window.addEventListener('click', function (event) {
        if (!event.target.matches('.action-btn') && !event.target.closest('.action-dropdown')) {
            closeAllDropdowns();
        }
    });

    function closeOtherDropdowns(currentButton) {
        var currentDropdown = currentButton.nextElementSibling;
        var allButtons = document.querySelectorAll('.action-btn');

        allButtons.forEach(function (button) {
            if (button !== currentButton) {
                var dropdownContent = button.nextElementSibling;
                dropdownContent.classList.remove('show');
            }
        });
    }

    function closeAllDropdowns() {
        var dropdowns = document.querySelectorAll('.action-dropdown');

        dropdowns.forEach(function (dropdown) {
            dropdown.querySelector('.dropdown-content').classList.remove('show');
        });
    }
});

</script>
<script>
    function deleteConcert(concert_ID) {
        if (confirm('Are you sure you want to delete this concert?')) {
            window.location.href = 'ManageEvent.php?action=delete&concert_ID=' + concert_ID;
        }
    }
</script>
</body>
</html>