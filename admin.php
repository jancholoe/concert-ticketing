<?php
include 'connection.php';
    
$selectConcertCount = "SELECT COUNT(*) AS concert_count FROM concert_details";
$resultConcertCount = mysqli_query($conn, $selectConcertCount);
$concertCount = mysqli_fetch_assoc($resultConcertCount)['concert_count'];


$selectOrderCount = "SELECT COUNT(*) AS order_count FROM transaction";
$resultOrderCount = mysqli_query($conn, $selectOrderCount);
$orderCount = mysqli_fetch_assoc($resultOrderCount)['order_count'];


$selectUserCount = "SELECT COUNT(*) AS user_count FROM user";
$resultUserCount = mysqli_query($conn, $selectUserCount);
$userCount = mysqli_fetch_assoc($resultUserCount)['user_count'];

// Fetch count from message table
$selectUserCount = "SELECT COUNT(*) AS message_count FROM message";
$resultUserCount = mysqli_query($conn, $selectUserCount);
$messageCount = mysqli_fetch_assoc($resultUserCount)['message_count'];

//retrieve
$select = "SELECT 
    concert_details.*, 
    transaction.*,
    customer.*
FROM 
    concert_details
JOIN 
    transaction ON concert_details.concert_ID = transaction.concert_ID
JOIN 
    customer ON transaction.transaction_ID = customer.customer_ID 
ORDER BY 
    transaction_ID DESC
LIMIT 6";



$result = mysqli_query($conn, $select);
// Check if the query was successful
if (!$result) {
    // Handle query execution error
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/admin/css/admin.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  color: black;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  
}
th{
    color: white;
    height: 30px;
    background-color: #15172b;
}

tr:nth-child(even) {
  background-color: lightgray;
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
        <span>DPS Tickets</span>
        </a>
    </li>
    <li class="active">
        <a href="#">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
        </a>
    </li>
    <li><a href="manage_users.php">
        <i class="fas fa-user"></i>
        <span>Users</span>
        </a>
    </li>
    <li>
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
            <h2>Dashboard</h2>
        </div>
        <!--
        <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search" />
        </div>
-->
</div>
        <div class="admin-section-panel admin-section-stats">
            <div class="admin-section-stats-panel">
                <i class="fa fa-ticket-alt" style="background-color: #cf4545"></i>
                <h2 style="color: #cf4545"><?php echo $concertCount; ?></h2>
                <h3>Concert Events</h3>
            </div>
            <div class="admin-section-stats-panel">
                <i class="fas fa-dollar-sign" style="background-color: #4547cf"></i>
                <h2 style="color: #4547cf"><?php echo $orderCount; ?></h2>
                <h3>Total orders</h3>
            </div>
            <div class="admin-section-stats-panel">
                <i class="fas fa-users" style="background-color: #000000"></i>
                <!--<i class="fas fa-ticket-alt"></i>-->
                <h2 style="color: black"><?php echo $userCount; ?></h2>
                <h3>Users</h3>
            </div>
            <div class="admin-section-stats-panel" style="border: none">
                <i class="fas fa-envelope" style="background-color: #3cbb6c"></i>
                <h2 style="color: #3cbb6c"><?php echo $messageCount; ?></h2>
                <h3>Messages</h3>
            </div>
        </div>
        <div class="admin-section-panel admin-section-panel1">
            <div class="admin-panel-section-header">
                <h2>Recent Orders</h2>
            </div>
            <div class="admin-panel-section-content">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Concert Name</th>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Tickets</th>
                        <th>Total Amount</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr align="center">
                                <td><?php echo $row['concert_name']; ?></td>
                                <td><?php echo $row['transaction_code']; ?></td>
                                <td><?php echo $row['customer_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['total_tickets']; ?></td>
                                <td>₱ <?php echo number_format( $row['total_amount']); ?></td>
                                <td><?php echo $row['payment_date']; ?></td>
                            </tr>
                    <?php }
                    ?>
                    </tbody>

                </table>
            </div>
        </div>
</div>
</body>
</html>