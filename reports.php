<?php
include 'connection.php';

// SQL query to join tables
$select =  "SELECT 
    concert_details.*, 
    sales.*, 
    transaction.*
FROM 
    concert_details
JOIN 
    sales ON concert_details.concert_ID = sales.sales_ID
JOIN 
    transaction ON sales.sales_ID = transaction.concert_ID";

$result = mysqli_query($conn, $select);
// Check if the query was successful
if (!$result) {
    // Handle query execution error
    echo "Error executing query: " . mysqli_error($conn);
    exit;
}

// Create an associative array to store merged values
$mergedRows = array();

// Loop through the result set
while ($row = mysqli_fetch_assoc($result)) {
    $concertID = $row['concert_ID'];

    // Check if the concert_ID already exists in the mergedRows array
    if (isset($mergedRows[$concertID])) {
        // If it does, accumulate the values
        $mergedRows[$concertID]['vipT'] += $row['vipT'];
        $mergedRows[$concertID]['lower_boxT'] += $row['lower_boxT'];
        $mergedRows[$concertID]['upper_boxT'] += $row['upper_boxT'];
        $mergedRows[$concertID]['gen_addT'] += $row['gen_addT'];
    } else {
        // If it doesn't, add the row to the mergedRows array
        $mergedRows[$concertID] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="assets/admin/css/reports.css">
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
  background-color: #747581;
}
/* start, jtables
label, .dataTables_info, .paginate_button{
    color: #fff !important;
}

.current{
    background-color: #dddddd !important; 
}
option{
    background-color: #2D2D2D !important;
}
.myTable{
    width: 10px !important;
}
/* end^*/
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
    <li>
        <a href="ManageEvent.php">
        <i class="fas fa-calendar-plus"></i>
        <span>Events</span>
        </a>
    </li>
    <li class="active">
        <a href="#">
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
            <h2>Reports</h2>
        </div>
        <!--
        <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search" />
        </div>
-->
</div>
        <div class="table-wrapper">
        <div class="table-1">

            <table id="myTable" class="display">
                <thead>
                   <tr>
                        <th>Concert Name</th>
                        <th>Available Tickets</th>
                        <th>Sold Tickets</th>   
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($mergedRows as $mergedRow) { ?>
            <tr>
                <td><?php echo $mergedRow['concert_name']; ?></td>
                <td>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">VIP SEAT:</span><?php echo $mergedRow['vipSeatS']; ?><br>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">LOWER BOX:</span><?php echo $mergedRow['lower_boxSeatS']; ?><br>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">UPPER BOX:</span><?php echo $mergedRow['upper_boxSeatS']; ?><br>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">GEN ADD:</span><?php echo $mergedRow['gen_addSeatS']; ?><br>
                </td>
                <td>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">VIP SEAT:</span><?php echo $mergedRow['vipT']; ?><br>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">LOWER BOX:</span><?php echo $mergedRow['lower_boxT']; ?><br>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">UPPER BOX:</span><?php echo $mergedRow['upper_boxT']; ?><br>
                    <span class="text-mute" style="color: black; font-size:14px; margin-right: 3px;">GEN ADD:</span><?php echo $mergedRow['gen_addT']; ?><br>
                </td>
                <td>
                    <div class="action-button">
                      <a class="action-btn" href="view_report.php?concert_ID=<?php echo $mergedRow['concert_ID']; ?>">
                        <i class="fas fa-eye"></i> View report
                      </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
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
</body>
</html>