<?php
include 'connection.php';




?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USERS</title>
    <link rel="stylesheet" href="assets/admin/css/manage_users.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;

        }

        th {
            background-color: #15172b;
            color: white;
            height: 50px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
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
            <li class="active"><a href="manage_users.php">
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
            <li><a href="logs.php" class="logout">
                    <i class="fas fa-book"></i>
                    <span>Logs</span>
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
                <h2>Manage Users</h2>
            </div>
            <!--
        <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search" />
        </div>
-->
        </div>

        <div class="content-wrapper">
            <!-- ===== List USERSS ====== -->
            <div class="Users">
                <div class="list">
                    <div class="cardHeader">
                        <h2>Users</h2>
                        <br>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $select = mysqli_query($conn, "SELECT * FROM users WHERE usertype='user'");
                            while ($row = mysqli_fetch_assoc($select)) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['FirstName']) . " " . htmlspecialchars($row['LastName']) . "</td>
                                    <td>" . htmlspecialchars($row['Username']) . "</td>
                                    <td>" . htmlspecialchars($row['Email']) . "</td>
                                    <td>
                                        <div class='action-dropdown'>
                                            <button class='action-btn'>Action <i class='fas fa-caret-down'></i></button>
                                            <div class='dropdown-content'>
                                                <a href='verify_user.php?id={$row['Id']}'>Verify</a>
                                                <a href='#' onclick='deleteUser({$row['Id']})'>Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>
    <!-- Action script(para sa dropdown)-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var actionButtons = document.querySelectorAll('.action-btn');

            actionButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    var dropdownContent = event.currentTarget.nextElementSibling;

                    // Close other dropdowns
                    closeOtherDropdowns(button);

                    // Toggle the 'show' class on the action-dropdown
                    dropdownContent.classList.toggle('show');
                });
            });

            // Close dropdowns if the user clicks outside
            window.addEventListener('click', function(event) {
                if (!event.target.matches('.action-btn') && !event.target.closest('.action-dropdown')) {
                    closeAllDropdowns();
                }
            });

            function closeOtherDropdowns(currentButton) {
                var currentDropdown = currentButton.nextElementSibling;
                var allButtons = document.querySelectorAll('.action-btn');

                allButtons.forEach(function(button) {
                    if (button !== currentButton) {
                        var dropdownContent = button.nextElementSibling;
                        dropdownContent.classList.remove('show');
                    }
                });
            }

            function closeAllDropdowns() {
                var dropdowns = document.querySelectorAll('.action-dropdown');

                dropdowns.forEach(function(dropdown) {
                    dropdown.querySelector('.dropdown-content').classList.remove('show');
                });
            }
        });
    </script>
    <script>
        function deleteUser(userID) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'delete_user.php?Id=' + userID;
            }
        }
    </script>
</body>

</html>