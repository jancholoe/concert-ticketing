<?php
include 'connection.php';

/* Debug: Output the current URL and $_GET parameters
echo "Current URL: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "<pre>";
print_r($_GET);
echo "</pre>";
// Check if concert_ID is provided in the URL
*/
if (isset($_GET['concert_ID'])) {
    $concertID = $_GET['concert_ID'];

    // Fetch additional information using the concert_ID
    $selectConcertDetails = "SELECT concert_name FROM concert_details WHERE concert_ID = $concertID";
    $resultConcertDetails = mysqli_query($conn, $selectConcertDetails);

    // Check if the query was successful
    if ($resultConcertDetails) {
        $concertDetails = mysqli_fetch_assoc($resultConcertDetails);
        $concertName = $concertDetails['concert_name'];
    } else {
        // Handle query execution error
        echo "Error executing query: " . mysqli_error($conn);
        exit;
    }

    // Date filtering logic
    if (isset($_GET['date_from']) && isset($_GET['date_to'])) {
        $dateFrom = mysqli_real_escape_string($conn, date('Y-m-d', strtotime($_GET['date_from'])));
        $dateTo = mysqli_real_escape_string($conn, date('Y-m-d', strtotime($_GET['date_to'])));
        $whereClause = " AND DATE(customer.payment_date) BETWEEN '$dateFrom' AND '$dateTo'";
    } else {
        $whereClause = "";
    }

    // Fetch relevant reports data for the specified concert_ID with date filtering
    $selectReports = "SELECT 
        concert_details.*, 
        transaction.*,
        customer.*
    FROM 
        concert_details
    JOIN 
        transaction ON concert_details.concert_ID = transaction.concert_ID
    JOIN 
        customer ON transaction.transaction_ID = customer.customer_ID
    WHERE 
        concert_details.concert_ID = $concertID
        $whereClause
    ORDER BY 
        transaction.transaction_ID DESC";

    $resultReports = mysqli_query($conn, $selectReports);

    // Check if the query was successful
    if (!$resultReports) {
        // Handle query execution error
        echo "Error executing query: " . mysqli_error($conn);
        exit;
    }

    // Calculate total sales dynamically based on the fetched data using SQL SUM
$selectTotalSales = "SELECT SUM(total_amount) AS total_sales FROM (
    SELECT 
        transaction.total_amount
    FROM 
        concert_details
    JOIN 
        transaction ON concert_details.concert_ID = transaction.concert_ID
    JOIN 
        customer ON transaction.transaction_ID = customer.customer_ID
    WHERE 
        concert_details.concert_ID = $concertID
        $whereClause
) AS total_sales_table";

$resultTotalSales = mysqli_query($conn, $selectTotalSales);
$totalSales = mysqli_fetch_assoc($resultTotalSales)['total_sales'];
} else {
    // Redirect or handle the case where concert_ID is not provided
    header("Location: reports.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="assets/admin/css/view_report.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- dataTables -->
    <link rel="stylesheet" href="assets/jTable.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="assets\admin\css\print.css">
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
        <a href="reports.php">
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
            <h2>Order Reports: <span style="font-weight: lighter;"><?php echo $concertName; ?></span></h2>
        </div>
</div>
        <div class="table-wrapper">
        <div class="table-1">

            <div class="form-row">
            <form id="filterForm" method="get" action="view_report.php">
    <input type="hidden" name="concert_ID" value="<?php echo $concertID; ?>">
    <div class="form-group">
        <label for="date_from" class="control-label" style="color: black; font-weight: bold;">Date From</label>
        <input type="date" name="date_from" class="form-control" value="" required>
    </div>
    <div class="form-group">
        <label for="date_to" class="control-label" style="color: black; font-weight: bold;">Date To</label>
        <input type="date" name="date_to" class="form-control" value="" required>
    </div>
    <div class="form-group">
        <button class="btn btn-flat btn-filter print-hide" id="filterBtn">Filter</button>
        <button class="btn btn-flat btn-print" type="button" id="print"><i class="fa fa-print"></i> Print</button>
    </div>
    <div class="form-group">
    <h2>Total Sales: ₱<?php echo number_format($totalSales); ?></h2>
    </div>
</form>

            </div>

            <table id="myTable" class="display">
                <thead>
                   <tr>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Tickets</th>   
                        <th>Total Amount</th>
                        <th>Purchase Date</th>
                </thead>
                   </tr>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultReports)) { ?>
                  <tr>
                    <td><?php echo $row['transaction_code']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['total_tickets']; ?></td>
                    <td>₱ <?php echo number_format($row['total_amount']); ?></td>
                    <td><?php echo $row['payment_date']; ?></td>
                 </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
</div>
<script>
        // Function to set the date values after form submission
        function setFormValues() {
            const params = new URLSearchParams(window.location.search);
            const dateFrom = params.get('date_from');
            const dateTo = params.get('date_to');

            document.querySelector("input[name='date_from']").value = dateFrom || '';
            document.querySelector("input[name='date_to']").value = dateTo || '';
        }

        // Execute the function on page load
        window.addEventListener("load", setFormValues);

        $(document).ready(function () {
            $("#filterBtn").on("click", function () {
                $("#filterForm").submit();
            });

            $("#print").on("click", function () {
    // Add the print stylesheet
    $("<link rel='stylesheet' href='assets/admin/css/print.css' media='print'>").appendTo("head");

    // Remove unnecessary elements from the body
    $(".sidebar, .header-wrapper, #print").hide();
    
    // Open the print dialog
    window.print();
    
    // Remove the print stylesheet and show the hidden elements after printing
    $("link[media=print]").remove();
    $(".sidebar, .header-wrapper, #print").show();
});

        });
</script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
</body>
</html>