<?php
include 'connection.php';

// Add user
if (isset($_POST['addSubmit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_BCRYPT); 

    $sql = "INSERT INTO user (fullname, username, password) VALUES ('$fullname', '$username', '$ $hash')";

    if ($conn->query($sql)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Update user
if (isset($_POST['editSubmit'])) {
    $editUserID = $_POST['editUserID'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $updateSql = "UPDATE user SET fullname = '$fullname', username = '$username', password = ' $hash' WHERE user_ID = $editUserID";
   

    if ($conn->query($updateSql)) {

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Retrieve users
$select = mysqli_query($conn, "SELECT * FROM user");

// delete user
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_ID'])) {
    $user_IDToDelete = $_GET['user_ID'];

    $deleteUserSql = "DELETE FROM user WHERE user_ID = ?";
    $deleteUserStmt = mysqli_prepare($conn, $deleteUserSql);
    mysqli_stmt_bind_param($deleteUserStmt, "i", $user_IDToDelete);

    if (mysqli_stmt_execute($deleteUserStmt)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        die("Database delete failed. Error: " . mysqli_error($conn));
    }
    mysqli_stmt_close($deleteUserStmt);
}
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

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  
}
th{
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
                                 <th>Fullname</th>
                                 <th> Username</th>
                                 <th>Password</th>      
                                 <th>Action</th>        
                            </tr>
                        </thead>
                        <tbody>
                  <?php while($row = mysqli_fetch_assoc($select)){ ?>
                        <tr>
                      <td><?php echo $row['fullname']; ?></td>
                      <td><?php echo $row['username']; ?></td>
                      <td><?php echo str_repeat('*', strlen($row['password'])); ?></td>

                      <td>  
                      <div class="action-dropdown">
                        <button class="action-btn">Action <i class="fas fa-caret-down"></i></button>
                        <div class="dropdown-content">
                        <a class="edit-btn" onclick="editUser(<?php echo $row['user_ID']; ?>, '<?php echo $row['fullname']; ?>', '<?php echo $row['username']; ?>', '<?php echo $row['password']; ?>')">Edit</a>
                            <a class="delete-btn" onclick="deleteUser(<?php echo $row['user_ID']; ?>)">Delete</a>
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

<!-- ===== ADD USERSS ====== -->
<div class="forms">
    <form method="post">
        <div class="title">Add User</div>
        <div class="form-group ic1">
            <input type="text" id="fullname" name="fullname" class="input" required>
            <div class="cut"></div>
            <label for="fullname" class="placeholder">Fullname</label>
        </div>
        <div class="form-group ic2">
            <input type="text" id="username" name="username" class="input" required>
            <div class="cut"></div>
            <label for="username" class="placeholder">Username</label>
        </div>
        <div class="form-group ic2">
            <input type="password" id="password" name="password" class="input" required>
            <div class="cut"></div>
            <label for="password" class="placeholder">Password</label>
        </div>
        <!-- Add a hidden input field to store the user ID for update -->
        <input type="hidden" id="editUserID" name="editUserID">
    <button type="submit" class="submit" name="addSubmit">Save</button>
    </form>
</div>

<!-- JavaScript for edit functionality -->
<script>
    function editUser(userID, fullname, username, password) {
        // Populate the form fields with the retrieved data
        document.getElementById('editUserID').value = userID;
        document.getElementById('fullname').value = fullname;
        document.getElementById('username').value = username;
        document.getElementById('password').value = password;

        // Change the form submission button name to trigger the editSubmit case
        document.querySelector('.submit').name = 'editSubmit';
    }
</script>
      
        

</div>
</div>
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
    function deleteUser(userID) {
        if (confirm('Are you sure you want to delete this user?')) {
            window.location.href = 'manage_users.php?action=delete&user_ID=' + userID;
        }
    }
</script>

</body>
</html>