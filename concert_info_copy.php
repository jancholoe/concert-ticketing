<?php
include 'connection.php';

// Check if concert_ID is set in the URL
if (isset($_GET['id'])) {
    // Retrieve the concert_ID from the URL
    $id = $_GET['id'];

    // Use JOIN to fetch data from both tables based on concert_ID
    $sql = "SELECT cd.*, cp.*, s.*
            FROM concert_details cd
            INNER JOIN concert_price cp ON cd.concert_ID = cp.price_ID
            LEFT JOIN sales s ON cd.concert_ID = s.sales_ID
            WHERE cd.concert_ID = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Now $row contains data from both tables
        // You can access fields like $row['cd_field'] for concert_details
        // and $row['cp_field'] for concert_price
        // Store relevant data in session for later use in payment.php
        $_SESSION['concert_info'] = [
            'concert_ID' => $row['concert_ID'],
            'vipSeatS' => $row['vipSeatS'],
            'lower_boxSeatS' => $row['lower_boxSeatS'],
            'upper_boxSeatS' => $row['upper_boxSeatS'],
            'gen_addSeatS' => $row['gen_addSeatS'],
            'vipPrice' => $row['vip'],
            'lowerBoxPrice' => $row['lower_box'],
            'upperBoxPrice' => $row['upper_box'],
            'genAddPrice' => $row['gen_add'],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/customer/css/info_copy.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
<style>
table {
    font-family: arial, sans-serif;
    width: 100%;
    color: black;
    table-layout: fixed; /* Ensures even spacing of columns */
    width: 95%;
    margin: 0 auto;
    border-collapse: collapse; /* Collapses borders between cells */
}

td, th {
    text-align: left;
    padding: 8px;
    border: 1px solid #ddd; /* Add border for better visibility */
}
td{
    height: 30px;
    color: #3F3844;
    font-weight: bold;
}
th {
    text-align: center;
    color: black;
    height: 50px;
    font-weight: bolder;
}

tr:nth-child(odd) td {
    background-color: lightgray;
}
.tdCenter{text-align: center;
}
span{
    font-weight: bolder;
    color: #3F3844 ;
}
</style>
</head>
<body>
<!-- <body style="background-color:#6e5a11;"> -->
    <div class="booking-panel">
        <div class="booking-panel-section booking-panel-section1">
        </div>
        <div class="booking-panel-section booking-panel-section2">
            <a href="customerpage copy.php"><i class="fas fa-2x fa-times"></i></a>
        </div>
        <div class="booking-panel-section booking-panel-section3">
            <div class="poster-box">
                <?php
                echo '<img src="img/uploads_copy/' . $row['poster'] . '" alt="">';
                ?>
            </div>
        </div>
        <div class="booking-panel-section booking-panel-section4">
            <div class="ticket-information">
                <h2 class="title"><?php echo $row['concert_name']; ?></h2>
                <div class="info">
                    <ul>
                        <li>
                            <span>
                            <i class="far fa-calendar-alt" style="color: blue;"></i>
                            <?php  
                                $schedule = new DateTime($row['schedule']);
                                echo $schedule->format('M d, Y');  
                            ?>
                            </span>
                        </li>
                        <li>
                            <span>
                            <i class="far fa-clock" style="color: blue;"></i>
                            <?php $Time = new DateTime($row['time']);
                        echo $Time->format('g:i A'); ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="buy">
        <!--
    <h2>About</h2>
    <hr class="aboutHR">
    <p class:"p-info"><?php echo $row['description']; ?></p>
    <hr class="aboutHR">
    -->
        <hr>
    <h3 style="text-align: left; font-size: 24px;  margin: 20px ">About</h3>
    <hr>
    <h3 style="font-weight: lighter; text-align:justify; margin: 20px; color: #3F3844">
    <?php echo $row['description']; ?>
    </h3>
    <hr>
            <table>
                <thead>
                    <tr>
                        <th>Seats</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>VIP</td>
                    <td class="tdCenter">₱ <?php echo number_format($row['vip']); ?></td>
                    <td>

                    <div class="quantity">
                        <span class="down" onClick='decreaseCount(event, this, "vipQuantity")'>-</span>
                        <input type="text" value="0" readonly id="vipQuantity" onInput="updateQuantities()" name="vipQuantity">
                        <span class="up"  onClick='increaseCount(event, this, "vipQuantity")'>+</span>
                        <?php echo $row['vipSeatS']; ?>
                    </div>

                    </td>
                </tr>
                <tr>
                    <td>LOWER BOX</td>
                    <td class="tdCenter">₱ <?php echo number_format($row['lower_box']); ?></td>
                    <td>
                        
                    <div class="quantity">
                        <span class="down" onClick='decreaseCount(event, this, "lowerBoxQuantity")'>-</span>
                        <input type="text" value="0" readonly id="lowerBoxQuantity" onInput="updateQuantities()" name="lowerBoxQuantity">
                        <span class="up"  onClick='increaseCount(event, this, "lowerBoxQuantity")'>+</span>
                        <?php echo $row['lower_boxSeatS']; ?>
                    </div>

                    </td>
                </tr>
                <tr>
                    <td>UPPER BOX</td>
                    <td class="tdCenter">₱ <?php echo number_format($row['upper_box']); ?></td>
                    <td>

                    <div class="quantity">
                        <span class="down" onClick='decreaseCount(event, this, "upperBoxQuantity")'>-</span>
                        <input type="text" value="0" readonly id="upperBoxQuantity" onInput="updateQuantities()" name="upperBoxQuantity">
                        <span class="up"  onClick='increaseCount(event, this, "upperBoxQuantity")'>+</span>
                        <?php echo $row['upper_boxSeatS']; ?>
                    </div>

                    </td>
                </tr>
                <tr>
                    <td>GENERAL ADMISSION</td>
                    <td class="tdCenter">₱ <?php echo number_format($row['gen_add']); ?></td>
                    <td>

                    <div class="quantity">
                        <span class="down" onClick='decreaseCount(event, this, "genAddQuantity")'>-</span>
                        <input type="text" value="0" readonly id="genAddQuantity" onInput="updateQuantities()" name="genAddQuantity">
                        <span class="up"  onClick='increaseCount(event, this, "genAddQuantity")'>+</span>
                        <?php echo $row['gen_addSeatS']; ?>
                    </div>

                    </td>
                </tr>
                </tbody>
            </table>
            <div class="buy-button">
            <input type="submit" name="Submit" value="Buy" id="buyButton" required onclick="openModal()">            
            </div>

                <!-- buy modal -->
            <div id="addNewModal" class="modal">
            <div class="modal-content">
                <div class="events">
                <form action="sample.php?id=<?php echo $_SESSION['concert_info']['concert_ID']; ?>" method="post" name="confirmationForm">
                    <h1>Review Ticket Details</h1>
                    <div class="form-group">
                    <!-- Add hidden inputs for quantities, total amount, and concert ID 
                    <input type="hidden" name="vipQuantity" id="vipQuantityModal"  value="0">
                    <input type="hidden" name="upperBoxQuantity" id="upperBoxQuantityModal" value="0">
                    <input type="hidden" name="lowerBoxQuantity" id="lowerBoxQuantityModal" value="0">
                    <input type="hidden" name="genAddQuantity" id="genAddQuantityModal" value="0">
                    <input type="hidden" name="totalAmount" id="totalAmountQuantityModal" value="0">
                    -->
                    <input type="hidden" name="concert_ID" value="<?php echo $_SESSION['concert_info']['concert_ID']; ?>">
                    <!-- Update the id of the hidden input in the modal -->
                    <p style="font-size: large;"><strong>VIP:</strong></p>
                    <input type="text" value="0" readonly id="vipQuantityModal" onInput="updateQuantities()" name="vipQuantity" class="hidden-input">
                    <p id="vipSeats" class="p-modal"></p>
                    <p style="font-size: large;"><strong>Upper Box:</strong></p>
                    <input type="text" value="0" readonly id="upperBoxQuantityModal" onInput="updateQuantities()" name="upperBoxQuantity" class="hidden-input">
                    <p id="upperBoxSeats" class="p-modal"></p>
                    <p style="font-size: large;"><strong>Lower Box:</strong></p>
                    <input type="text" value="0" readonly id="lowerBoxQuantityModal" onInput="updateQuantities()" name="lowerBoxQuantity" class="hidden-input">
                    <p id="lowerBoxSeats" class="p-modal"></p>
                    <p style="font-size: large;"><strong>General Admission:</strong></p>
                    <input type="text" value="0" readonly id="genAddQuantityModal" onInput="updateQuantities()" name="genAddQuantity" class="hidden-input">
                    <p id="genAddSeats" class="p-modal"></p>
                    <hr><p style="font-size: large;"><strong>Total amount:</strong></p>
                    <input type="text" value="0" readonly id="totalAmountQuantityModal" onInput="updateQuantities()" name="totalAmount" class="hidden-input">
                    <p id="totalAmount" style="font-size: large;">₱0</p>
                    </div>
                    <div class="form-button">
                    <label for=""></label>
                    <button type="submit" class="" name="confirm" required>Confirm</button>
                    <a class="cancel-modal" href="#" onclick="closeModal(event)">Cancel</a>
                    <!--<a class="btn btn-flat btn-default" href="branch.php">Cancel</a>-->
                    </div>
                </form>
                </div>
            </div>
            </div>


<!--PARA SA QUANTITY-->
<script>
// Store the initial quantities from the sales table in variables
var initialVipQuantity = <?php echo $row['vipSeatS']; ?>;
var initialLowerBoxQuantity = <?php echo $row['lower_boxSeatS']; ?>;
var initialUpperBoxQuantity = <?php echo $row['upper_boxSeatS']; ?>;
var initialGenAddQuantity = <?php echo $row['gen_addSeatS']; ?>;

// Store the initial seat prices in variables
var vipPrice = <?php echo $row['vip']; ?>;
var lowerBoxPrice = <?php echo $row['lower_box']; ?>;
var upperBoxPrice = <?php echo $row['upper_box']; ?>;
var genAddPrice = <?php echo $row['gen_add']; ?>;

// Function to update the quantities and total amount
function updateQuantities() {
    console.log('Updating quantities...');

    // Update quantities based on input values
    var vipQuantity = parseInt(document.getElementById('vipQuantity').value);
    var lowerBoxQuantity = parseInt(document.getElementById('lowerBoxQuantity').value);
    var upperBoxQuantity = parseInt(document.getElementById('upperBoxQuantity').value);
    var genAddQuantity = parseInt(document.getElementById('genAddQuantity').value);
    
    console.log('VIP Quantity:', vipQuantity);
    
    // Display the quantities in the modal
    document.getElementById('vipSeats').innerText = vipQuantity;
    document.getElementById('upperBoxSeats').innerText = upperBoxQuantity;
    document.getElementById('lowerBoxSeats').innerText = lowerBoxQuantity;
    document.getElementById('genAddSeats').innerText = genAddQuantity;

    // Calculate and display the total amount
    var totalAmount =
        vipQuantity * vipPrice +
        lowerBoxQuantity * lowerBoxPrice +
        upperBoxQuantity * upperBoxPrice +
        genAddQuantity * genAddPrice;

    // Convert totalAmount to a float and display it
    totalAmount = parseFloat(totalAmount.toFixed(2)); // Ensure two decimal places

    let formattedAmount = totalAmount.toLocaleString('en-US');

// Display the formatted number with the currency symbol
document.getElementById('totalAmount').innerText = "₱ " + formattedAmount;

    // Update the hidden input value in the modal dynamically
    document.getElementById('vipQuantityModal').value = vipQuantity;
    document.getElementById('upperBoxQuantityModal').value = upperBoxQuantity;
    document.getElementById('lowerBoxQuantityModal').value = lowerBoxQuantity;
    document.getElementById('genAddQuantityModal').value = genAddQuantity;
    document.getElementById('totalAmountQuantityModal').value = totalAmount; // Update this line
    
    console.log('totalAmount:', totalAmount);
    // Check if all quantities are zero, if yes, do not show the modal
    if (vipQuantity === 0 && lowerBoxQuantity === 0 && upperBoxQuantity === 0 && genAddQuantity === 0) {
        modal.style.display = 'none';
    }
}

// Get the modal
var modal = document.getElementById('addNewModal');

function openModal() {
    var modal = document.getElementById('addNewModal');
    modal.style.display = 'block';
}

function closeModal(event) {
    event.preventDefault(); // Prevent the default behavior of the anchor link
    var modal = document.getElementById('addNewModal');
    modal.style.display = 'none';

    // Check if the ID is set in the URL
    const idParam = new URLSearchParams(window.location.search).get('id');
    
    // Redirect to the main page with the ID parameter without refreshing
    history.pushState(null, null, 'concert_info_copy.php?id=' + idParam);
}

// Get the "Buy" button
var buyButton = document.getElementById('buyButton');

// When the user clicks the "Buy" button, update quantities and open the modal
buyButton.addEventListener('click', function (event) {
    event.preventDefault();
    updateQuantities(); // Make sure quantities are updated before opening the modal

    // Show the modal only if at least one quantity is greater than 0
    if (vipQuantity > 0 || lowerBoxQuantity > 0 || upperBoxQuantity > 0 || genAddQuantity > 0) {
        openModal();
    }
});

// Get the "Confirm" button
var confirmButton = document.querySelector('button[name="confirm"]');

// When the user clicks the "Confirm" button, update quantities and open the modal
confirmButton.addEventListener('click', function (event) {
    event.preventDefault();
    updateQuantities(); // Make sure quantities are updated before opening the modal

    // Check if all quantities are zero, if yes, do not show the modal
    if (vipQuantity === 0 && lowerBoxQuantity === 0 && upperBoxQuantity === 0 && genAddQuantity === 0) {
        modal.style.display = 'none';
    } else {
        // Submit the form to payment.php
        document.querySelector('form[name="confirmationForm"]').submit();
    }
});

// Additional functions for quantity increase/decrease
function increaseCount(event, element, inputId) {
    var input = element.previousElementSibling;
    var value = parseInt(input.value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    input.value = value;
    updateQuantities(); // Update quantities on increase
}

function decreaseCount(event, element, inputId) {
    var input = element.nextElementSibling;
    var value = parseInt(input.value, 10);
    if (value > 0) {
        value = isNaN(value) ? 0 : value;
        value--;
        input.value = value;
        updateQuantities(); // Update quantities on decrease
    }
}
</script>

</body>
</html>