<?php
include 'connection.php';

/*retrieve*/
$sql = "SELECT * FROM message";
$result = mysqli_query($conn, $sql);

/* delete message */
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['message_ID'])) {
    $message_IDToDelete = $_GET['message_ID'];

    $deleteMessageSql = "DELETE FROM message WHERE message_ID = ?";
    $deleteMessageStmt = mysqli_prepare($conn, $deleteMessageSql);
    mysqli_stmt_bind_param($deleteMessageStmt, "i", $message_IDToDelete);

    if (mysqli_stmt_execute($deleteMessageStmt)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        die("Database delete failed. Error: " . mysqli_error($conn));
    }
    mysqli_stmt_close($deleteMessageStmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
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

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;

        }

        th {
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
        /*action button*/
        .action-button {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .action-btn {
            background-color: transparent;
            color: red;
            padding: 10px;
            border: 1px solid black;
            cursor: pointer;
            display: flex;
            align-items: center;
            text-align: center;
            width: 80px !important;
            font-size: 14px;
            text-decoration: none;
        }

        .action-btn i {
            margin-right: 5px;
            font-size: 14px;
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
            <li>
                <a href="ManageEvent.php">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Events</span>
                </a>
            </li>
            <li class="">
                <a href="reports.php">
                    <i class="fas fa-receipt"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="active">
                <a href="#">
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
                <h2>Messages</h2>
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
                            <th>Date</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row['message_date']; ?></td>
                                <td><?php echo $row['firstname']; ?></td>
                                <td><?php echo $row['lastname']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['MSG']; ?></td>
                                <td>
                                    <div class="action-button">
                                        <button class="action-btn" onclick="deleteMessage(<?php echo $row['message_ID']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                            </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        function deleteMessage(message_ID) {
            if (confirm('Are you sure you want to delete this message?')) {
                window.location.href = 'message.php?action=delete&message_ID=' + message_ID;
            }
        }
    </script>
</body>

</html>