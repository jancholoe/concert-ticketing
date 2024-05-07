<?php
include 'connection.php';

/*retrive*/ 
$sql = "SELECT * FROM concert_details";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concert tickets</title>
    <link rel="stylesheet" href="assets/customer/css/allconcert.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
</head>
<body>
    <header>
        <a href="#" class="logo">DPS Tickets</a>
        <div class="group">
            <ul class="navigation">
                <li><a href="#">HOME</a></li>
                <li><a href="#">CONCERTS</a></li>
                <li><a href="About.php">ABOUT</a></li>
            </ul>
            <div class="search">
                <span class="icon">
                <i class="fas fa-search searchBtn"></i>
                <i class="fas fa-times closeBtn"></i>
                </span>
            </div>

        </div>
            <div class="searchBox">
                <input type="text" placeholder="Search Concerts...">
            </div>
    </header>
    <div id="home-section-1" class="movie-show-container">
        <h1>All Concerts</h1>
        
        <div class="movies-container">

            <?php
            if ($result = mysqli_query($conn, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    $concertCount = 0;
                    echo '<div class="movies-row">'; // Start the first row
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<div class="movie-box">';
                        echo '<img src="img/uploads_copy/' . $row['poster'] . '" alt=" " >';
                        echo '<div class="movie-info ">';
                        echo '<h3>' . $row['concert_name'] . '</h3>';
                        echo '<a href="concert_info_copy.php?id=' . $row['concert_ID'] . '"><i class="fas fa-ticket-alt"></i> Buy Tickets</a>';
                        echo '</div>';
                        echo '</div>';
                        
                        $concertCount++;

                        if ($concertCount % 4 == 0) {
                            // End the row and start a new one for every 4 concerts
                            echo '</div><div class="movies-row">';
                        }
                    }

                    // Close the last row if there are remaining concerts
                    echo '</div>';

                    mysqli_free_result($result);
                } else {
                    echo '<h4 class="no-annot">No Available concerts right now</h4>';
                }
            } else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
            }
            
            // Close connection
            mysqli_close($conn);
            ?>
        </div>
    </div>
    <!--para sa search-->
    <script>
        let searchBtn = document.querySelector('.searchBtn');
        let closeBtn = document.querySelector('.closeBtn');
        let searchBox = document.querySelector('.searchBox')
        searchBtn.onclick = function(){
            searchBox.classList.add('active');
            closeBtn.classList.add('active');
            searchBtn.classList.add('active');
        }
        closeBtn.onclick = function(){
            searchBox.classList.remove('active');
            closeBtn.classList.remove('active');
            searchBtn.classList.remove('active');
        }
    </script>
</body>
</html>