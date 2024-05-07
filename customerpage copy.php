<?php
include 'connection.php';

/* retrieve */
$sql = "SELECT * FROM concert_details";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concert tickets</title>
    <link rel="stylesheet" href="assets/customer/css/customer copy.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
</head>
<body>
    <header>
        <a href="#" class="logo">DPS Tickets</a>
        <div class="group">
            <ul class="navigation">
                <li><a href="#">HOME</a></li>
                <li><a href="About.php">CONTACT US</a></li>
                <li><a href="php/logout.php"><button class="btn">Log Out</button> </a></li>
            </ul>
            <div class="search">
                <span class="icon">
                    <i class="fas fa-search searchBtn"></i>
                    <i class="fas fa-times closeBtn"></i>
                </span>
            </div>
        </div>
        <div class="searchBox">
            <input type="text" id="searchInput" placeholder="Search Concerts...">
        </div>
    </header>
    <div id="home-section-1" class="movie-show-container">
        <h1>Featured Concerts</h1>
        <h3>Buy now</h3>

        <div class="movies-container" id="moviesContainer">
            <?php
            if ($result = mysqli_query($conn, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<div class="movie-box">';
                        echo '<img src="img/uploads_copy/' . $row['poster'] . '" alt=" " >';
                        echo '<div class="movie-info ">';
                        echo '<h3>' . $row['concert_name'] . '</h3>';
                        echo '<a href="concert_info_copy.php?id=' . $row['concert_ID'] . '"><i class="fas fa-ticket-alt"></i> Buy Tickets</a>';
                        echo '</div>';
                        echo '</div>';
                    }
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

<!-- JavaScript for search functionality -->
<script>
    let searchInput = document.getElementById('searchInput');
    let moviesContainer = document.getElementById('moviesContainer');
    let searchBox = document.querySelector('.searchBox');
    let closeBtn = document.querySelector('.closeBtn');
    let searchBtn = document.querySelector('.searchBtn');

    // Event listener for search input
    searchInput.addEventListener('input', function() {
        filterConcerts(this.value.toLowerCase());
    });

    // Event listener for search button
    searchBtn.onclick = function(){
        searchBox.classList.add('active');
        closeBtn.classList.add('active');
    }

    // Function to filter concerts based on search input
    function filterConcerts(searchTerm) {
        let concertBoxes = moviesContainer.getElementsByClassName('movie-box');

        for (let i = 0; i < concertBoxes.length; i++) {
            let concertBox = concertBoxes[i];
            let concertName = concertBox.querySelector('h3').innerText.toLowerCase();

            // Check if the concert name contains the search term
            if (concertName.includes(searchTerm)) {
                concertBox.style.display = 'block';
            } else {
                concertBox.style.display = 'none';
            }
        }

        // Show the search box when there are search results
        if (searchTerm !== '') {
            searchBox.classList.add('active');
            closeBtn.classList.add('active');
        } else {
            searchBox.classList.remove('active');
            closeBtn.classList.remove('active');
        }

        // Adjust the layout of visible containers
        adjustContainerLayout();
    }

    // Event listener for close button
    closeBtn.onclick = function(){
        searchBox.classList.remove('active');
        closeBtn.classList.remove('active');
        // Adjust the layout of visible containers
        adjustContainerLayout();
    }

    // Function to adjust the layout of visible containers
    function adjustContainerLayout() {
        let visibleContainers = moviesContainer.querySelectorAll('.movie-box[style="display: block;"]');
        let containerCount = visibleContainers.length;

        if (containerCount > 0) {
            // Calculate the width for each visible container
            let containerWidth = 100 / containerCount;
            
            // Set the width for each visible container
            for (let i = 0; i < visibleContainers.length; i++) {
                visibleContainers[i].style.width = containerWidth + '%';
            }
        }
    }
</script>

</body>
</html>
