<?php
include 'connection.php';
require_once('tcpdf/tcpdf.php');
require_once('PHPMailer-master/src/Exception.php');
require_once('PHPMailer-master/src/PHPMailer.php');
require_once('PHPMailer-master/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to mask card number
function maskCardNumber($cardNumber) {
    // Mask all digits except the last four
    return substr($cardNumber, 0, -4) . str_repeat('*', 4);
}
// Function to validate and mask card number before processing payment
function validateAndMaskCardNumber($cardNumber) {
    // Validate card number format (simple example using regex)
    if (!preg_match('/^\d{16}$/', $cardNumber)) {
        throw new Exception('Invalid card number format.');
    }
    // Mask the card number
    return maskCardNumber($cardNumber);
}

function generateTrackingCode($length = 10) {
    $characters = '0123456789';
    $trackingCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomChar = $characters[rand(0, strlen($characters) - 1)];
        $trackingCode .= $randomChar;
    }
    return $trackingCode;
}

function calculateTotalQuantity($vipQuantity, $upperBoxQuantity, $lowerBoxQuantity, $genAddQuantity) {
    return $vipQuantity + $upperBoxQuantity + $lowerBoxQuantity + $genAddQuantity;
}   

function createTicketLayout($pdf, $concertDetails, $seatType) {
    $pdf->SetFont('times', 'B', 12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $boxWidth = 150; // Adjust the width of the rectangle
    $pdf->Rect($x + (190 - $boxWidth) / 2, $y, $boxWidth, 80); // Center the box on the page and adjust the height

    // Center the text inside the box
    $pdf->SetXY($x + (190 - $boxWidth) / 2 + 5, $y + 5);

    // Move "KRK Tickets" text to the left side
    $pdf->SetXY($x + 5, $y + 5);

    $pdf->Cell(0, 8, 'DPS Tickets', 0, 1, 'C');
    $pdf->SetFont('times', '', 12); // Set font to regular
    $pdf->Cell(0, 8, '----------------------------------------------------------------------------------------------------------', 0, 1, 'C'); // Line
    $pdf->Cell(0, 10, $concertDetails['concert_name'], 0, 1, 'C');
    $pdf->SetFont('times', 'B', 12); // Set font back to bold
    $pdf->Cell(0, 8, 'Concert', 0, 1, 'C');
    $pdf->SetFont('times', '', 12); // Set font to regular

    // Format the date here
    $scheduleDateTime = new DateTime($concertDetails['schedule'] . ' ' . $concertDetails['time']);
    $formattedSchedule = $scheduleDateTime->format('F j, Y g:i A');

    $pdf->Cell(0, 8, '--------------------------------------', 0, 1, 'C'); // Line
    $pdf->Cell(0, 8, $formattedSchedule, 0, 1, 'C');
    $pdf->Cell(0, 8, '--------------------------------------', 0, 1, 'C'); // Line
    $pdf->SetFont('times', 'B', 12); // Set font back to bold
    $pdf->Cell(0, 10, 'Seat Type: ' . $seatType, 0, 1, 'C');
}

ob_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Confirm'])) {
    // Retrieve form data
    $vipQuantity = isset($_POST['vipQuantity']) ? intval($_POST['vipQuantity']) : 0;
    $upperBoxQuantity = isset($_POST['upperBoxQuantity']) ? intval($_POST['upperBoxQuantity']) : 0;
    $lowerBoxQuantity = isset($_POST['lowerBoxQuantity']) ? intval($_POST['lowerBoxQuantity']) : 0;
    $genAddQuantity = isset($_POST['genAddQuantity']) ? intval($_POST['genAddQuantity']) : 0;
    $totalAmount = isset($_POST['totalAmount']) ? floatval($_POST['totalAmount']) : 0;
    $concert_ID = isset($_POST['concert_ID']) ? intval($_POST['concert_ID']) : 0;
    $customerName = isset($_POST['name']) ? $_POST['name'] : '';
    $customerEmail = isset($_POST['email']) ? $_POST['email'] : '';
    $cardNumber = isset($_POST['card']) ? $_POST['card'] : '';
    $expirationMonth = isset($_POST['month']) ? $_POST['month'] : '';
    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';

    // Mask the card number for logging or display purposes
    $maskedCardNumber = validateAndMaskCardNumber($cardNumber);

    // Generate random tracking code
    $transactionCode = generateTrackingCode();

    // Perform database updates
    

    // Calculate total quantity
    $totalTickets = calculateTotalQuantity($vipQuantity, $upperBoxQuantity, $lowerBoxQuantity, $genAddQuantity);

    // Insert into the transaction table
    $insertTransactionQuery = "INSERT INTO transaction (concert_ID, total_tickets, total_amount, vipT, lower_boxT, upper_boxT, gen_addT) 
                               VALUES 
                              ($concert_ID, $totalTickets, $totalAmount, $vipQuantity, $lowerBoxQuantity, $upperBoxQuantity, $genAddQuantity)";
    mysqli_query($conn, $insertTransactionQuery);
    echo "Error: " . mysqli_error($conn);
    echo "Total Amount: " . $totalAmount;

    // Update the sales table
    $updateSalesQuery = "UPDATE sales SET 
                        vipSeatS = vipSeatS - $vipQuantity,
                        lower_boxSeatS = lower_boxSeatS - $lowerBoxQuantity,
                        upper_boxSeatS = upper_boxSeatS - $upperBoxQuantity,
                        gen_addSeatS = gen_addSeatS - $genAddQuantity
                        WHERE sales_ID = $concert_ID";
    if (!mysqli_query($conn, $updateSalesQuery)) {
        echo "Error: " . mysqli_error($conn);
        echo "VIP Quantity: " . $vipQuantity;
    }

    // Insert into the customer table
    $insertCustomerQuery = "INSERT INTO customer (concert_ID, email, customer_name, transaction_code) VALUES ($concert_ID, '$customerEmail', '$customerName','$transactionCode')";
    if (!mysqli_query($conn, $insertCustomerQuery)) {
        echo "Error: " . mysqli_error($conn);
    }

    // Create a PDF instance
    ob_clean();
    $pdf = new TCPDF();

    // Set font
    $pdf->SetFont('times', 'B', 12);

    // Fetch concert details based on concert_ID
    $concert_ID = isset($_SESSION['concert_info']['concert_ID']) ? $_SESSION['concert_info']['concert_ID'] : 0;
    $fetchConcertDetailsQuery = "SELECT concert_name, time, schedule FROM concert_details WHERE concert_ID = $concert_ID";
    $result = mysqli_query($conn, $fetchConcertDetailsQuery);

    // Check if the query was successful
    if ($result) {
        // Fetch the data as an associative array
        $concertDetails = mysqli_fetch_assoc($result);

        // Define seat types and quantities
        $seatTypes = array('VIP' => $vipQuantity, 'Upper Box' => $upperBoxQuantity, 'Lower Box' => $lowerBoxQuantity, 'General Admission' => $genAddQuantity);

        // Loop through each seat type and add a page with the ticket layout based on quantity
        foreach ($seatTypes as $seatType => $quantity) {
            for ($i = 0; $i < $quantity; $i++) {
                $pdf->AddPage();
                createTicketLayout($pdf, $concertDetails, $seatType);
            }
        }

        // Output the PDF as a download (pag S = save pag D = download)
        $pdfContent = $pdf->Output('tickets.pdf', 'S');
    } else {
        echo "Error fetching concert details: " . mysqli_error($conn);
    }

    // Create a PHPMailer instance
  // Create a PHPMailer instance
// Create a PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';  // SMTP server address
    $mail->SMTPAuth   = true;               // Enable SMTP authentication
    $mail->Username   = 'krkticket@gmail.com'; // SMTP username (your Gmail address)
    $mail->Password   = 'efzmiwbhnlqfdmrd';     // SMTP password (your Gmail password)
    $mail->SMTPSecure = 'tls';               // Enable TLS encryption
    $mail->Port       = 587;                 // TCP port to connect to
    $mail->SMTPAutoTLS = false;              // Disable automatic TLS negotiation
    $mail->SMTPDebug = 2;                    // Enable verbose debugging

    // Sender
    $mail->setFrom('krkticket@gmail.com', 'DPS Tickets'); // Sender's email address and name

    // Recipient
    $mail->addAddress($customerEmail, $customerName); // Recipient's email address and name

    // Attach the PDF
    $mail->addStringAttachment($pdfContent, 'tickets.pdf');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Concert Ticket Purchase';
    $mail->Body    = 'Thank you, ' . $customerName . ', for purchasing tickets! Attached is your concert ticket pdf. Order ID: ' . $transactionCode;
   

    // Send the email
    $mail->send();

    echo 'Email sent successfully';

    // JavaScript delay for 5 seconds before redirecting
    echo '<script>
        setTimeout(function(){
            window.location.href = "success.php?name=' . urlencode($customerName) . '";
        }, 5000); // 5000 milliseconds = 5 seconds
      </script>';
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


    // End output buffering and flush the output
    ob_end_flush();
    exit(); // Ensure no further code is executed after redirection
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_method'])) {
    // Assuming you receive the selected payment method and amount via POST
    $selectedPaymentMethod = $_POST['payment_method'];
    $amount = isset($_POST['totalAmount']) ? $_POST['totalAmount'] : 0; // Use totalAmount instead of amount

    // Handle form submission based on the selected payment method
    switch ($selectedPaymentMethod) {
        case 'card':
            // Handle credit card payment
            break;
        case 'gcash':
            // Handle GCash payment
            // Here you can call a function to process GCash payment
            generateGCashPayment($amount);
            break;
        default:
            // Handle other cases or show an error message
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concert tickets</title>
    <link rel="stylesheet" href="assets/customer/css/payment.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <style></style>
</head>
<body>
    <header>
        <a href="#" class="logo">DPS Tickets</a>
        <div class="group">
            <ul class="navigation">
                <li><a href="customerpage copy.php">HOME</a></li>
                <li><a href="About.php">CONTACT US</a></li>
            </ul>
        </div>
    </header>
    <div class="content">
        <div id="Credit Card" class="tabcontent">
            <h3 style="margin-top: 70px;">Select your payment method</h3>
            <form action="payment.php?id=<?php echo isset($_SESSION['concert_info']['concert_ID']) ? $_SESSION['concert_info']['concert_ID'] : ''; ?>" method="post" name="confirmationForm">
                <!-- Include other form fields -->
                <input type="hidden" name="concert_ID" value="<?php echo isset($_SESSION['concert_info']['concert_ID']) ? $_SESSION['concert_info']['concert_ID'] : ''; ?>">
                <input type="hidden" name="totalAmount" value="<?php echo isset($_POST['totalAmount']) ? $_POST['totalAmount'] : ''; ?>">
                <input type="hidden" name="vipQuantity" value="<?php echo isset($_POST['vipQuantity']) ? $_POST['vipQuantity'] : ''; ?>">
                <input type="hidden" name="lowerBoxQuantity" value="<?php echo isset($_POST['lowerBoxQuantity']) ? $_POST['lowerBoxQuantity'] : ''; ?>">
                <input type="hidden" name="upperBoxQuantity" value="<?php echo isset($_POST['upperBoxQuantity']) ? $_POST['upperBoxQuantity'] : ''; ?>">
                <input type="hidden" name="genAddQuantity" value="<?php echo isset($_POST['genAddQuantity']) ? $_POST['genAddQuantity'] : ''; ?>">

                <p>Name</p>
                <input type="text" name="name" placeholder="Name" required>

                <p>Email</p>
                <input type="email" name="email" id="email" placeholder="Email" required>

                <p>Card or Gcash Number</p>
                <input type="text" name="card_or_gcash" id="card_or_gcash" placeholder="Enter your Number" required>

                <p>Payment Method</p>
                <select name="payment_method">
                    <option value="card">Credit Card</option>
                    <option value="gcash">GCash</option>
                </select>

                <div class="center">
                    <input type="submit" name="Confirm" value="CONFIRM & PROCEED" id="submit" class="submit-button">
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#card_or_gcash').inputmask({
                mask: '9999 9999 9999 9999', // Card number mask (format: '9999 9999 9999 9999')
                placeholder: ' ',
                showMaskOnHover: false,
                showMaskOnFocus: true,
                clearIncomplete: true,
                autoUnmask: true,
                onincomplete: function(){ 
                    alert('Card number is incomplete'); 
                }
            });
        });
    </script>
</body>
</html>
