<?php
include 'connection.php';
require_once('tcpdf/tcpdf.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);



function maskCardNumber($cardNumber)
{
    return substr($cardNumber, 0, -4) . str_repeat('*', 4);
}

function getConcertDetails($concertID, $conn)
{
    $sql = "SELECT concert_name, schedule, time FROM concert_details WHERE concert_ID = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $concertID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            return $row;
        } else {
            throw new Exception('No concert details found.');
        }
    } else {
        throw new Exception('Database query failed.');
    }
}

function validateAndMaskCardNumber($cardNumber)
{
    if (!preg_match('/^\d{16}$/', $cardNumber)) {
        throw new Exception('Invalid card number format.');
    }
    return maskCardNumber($cardNumber);
}

function generateTrackingCode($length = 10)
{
    $characters = '0123456789';
    $trackingCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomChar = $characters[rand(0, strlen($characters) - 1)];
        $trackingCode .= $randomChar;
    }
    return $trackingCode;
}

function calculateTotalQuantity($vipQuantity, $upperBoxQuantity, $lowerBoxQuantity, $genAddQuantity)
{
    return $vipQuantity + $upperBoxQuantity + $lowerBoxQuantity + $genAddQuantity;
}

function createTicketLayout($pdf, $concertDetails, $seatType)
{
    $pdf->SetFont('times', 'B', 12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $boxWidth = 150;
    $pdf->Rect($x + (190 - $boxWidth) / 2, $y, $boxWidth, 80);
    $pdf->SetXY($x + 5, $y + 5);
    $pdf->Cell(0, 8, 'DPS Tickets', 0, 1, 'C');
    $pdf->Cell(0, 10, $concertDetails['concert_name'], 0, 1, 'C');
    $scheduleDateTime = new DateTime($concertDetails['schedule'] . ' ' . $concertDetails['time']);
    $formattedSchedule = $scheduleDateTime->format('F j, Y g:i A');
    $pdf->Cell(0, 8, $formattedSchedule, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Seat Type: ' . $seatType, 0, 1, 'C');
}

function logEvent($conn, $userId, $eventType, $description)
{
    $sql = "INSERT INTO logs (user_id, event_type, description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $userId, $eventType, $description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Confirm'])) {
    $concert_ID = $_POST['concert_ID'];
    $vipQuantity = $_POST['vipQuantity'];
    $upperBoxQuantity = $_POST['upperBoxQuantity'];
    $lowerBoxQuantity = $_POST['lowerBoxQuantity'];
    $genAddQuantity = $_POST['genAddQuantity'];
    $totalAmount = $_POST['totalAmount'];
    $customerEmail = $_POST['email'];
    $customerName = $_POST['name'];

    // Prepare the PDF for tickets
    $pdf = new TCPDF();
    $pdf->SetFont('times', 'B', 12);
    $concertDetails = getConcertDetails($concert_ID, $conn);

    // Generate a transaction code
    $transactionCode = generateTrackingCode();

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        $totalTickets = calculateTotalQuantity($vipQuantity, $upperBoxQuantity, $lowerBoxQuantity, $genAddQuantity);

    
        $insertTransactionQuery = "INSERT INTO transaction (concert_ID, total_tickets, total_amount, vipT, lower_boxT, upper_boxT, gen_addT) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertTransactionQuery);
        mysqli_stmt_bind_param($stmt, 'iidiiii', $concert_ID, $totalTickets, $totalAmount, $vipQuantity, $lowerBoxQuantity, $upperBoxQuantity, $genAddQuantity);
        mysqli_stmt_execute($stmt);
        logEvent($conn, $_SESSION['id'] ?? null, 'Payment Initiated', "User {$name} initiated payment with masked card: " . maskCardNumber($cardNumber));
        $insertCustomerQuery = "INSERT INTO customer (concert_ID, email, customer_name, transaction_code) 
                                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertCustomerQuery);
        mysqli_stmt_bind_param($stmt, 'isss', $concert_ID, $customerEmail, $customerName, $transactionCode);
        mysqli_stmt_execute($stmt);

        $updateSalesQuery = "UPDATE sales SET 
                             vipSeatS = vipSeatS - ?, lower_boxSeatS = lower_boxSeatS - ?, upper_boxSeatS = upper_boxSeatS - ?, gen_addSeatS = gen_addSeatS - ?
                             WHERE sales_ID = ?";
        $stmt = mysqli_prepare($conn, $updateSalesQuery);
        mysqli_stmt_bind_param($stmt, 'iiiii', $vipQuantity, $lowerBoxQuantity, $upperBoxQuantity, $genAddQuantity, $concert_ID);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) < 1) {
            throw new Exception("Failed to update sales.");
        }

        mysqli_commit($conn);

        // Generate tickets PDF
        foreach (array('VIP' => $vipQuantity, 'Upper Box' => $upperBoxQuantity, 'Lower Box' => $lowerBoxQuantity, 'General Admission' => $genAddQuantity) as $seatType => $quantity) {
            for ($i = 0; $i < $quantity; $i++) {
                $pdf->AddPage();
                createTicketLayout($pdf, $concertDetails, $seatType);
            }
        }

        $pdfContent = $pdf->Output('tickets.pdf', 'S');

        // Send the email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'krkticket@gmail.com'; // Your SMTP username
        $mail->Password = 'efzmiwbhnlqfdmrd'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('krkticket@gmail.com', 'DPS Tickets');
        $mail->addAddress($customerEmail, $customerName);
        $mail->addStringAttachment($pdfContent, 'tickets.pdf');
        $mail->isHTML(true);
        $mail->Subject = 'Your Concert Tickets';
        $mail->Body = 'Thank you for your purchase. Please find attached your tickets.';

        $mail->send();
        echo "<script>alert('Tickets have been sent to your email.');</script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Error processing your request: " . $e->getMessage() . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concert Tickets</title>
    <link rel="stylesheet" href="assets/customer/css/payment.css">
    <link rel="stylesheet" href="assets/fontawesome-free-5.15.4-web/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/jquery.inputmask.min.js"></script>
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
            <form method="post">
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
        $(document).ready(function() {
            $('#card_or_gcash').inputmask({
                mask: '9999 9999 9999 9999', // Card number mask (format: '9999 9999 9999 9999')
                placeholder: ' ',
                showMaskOnHover: false,
                showMaskOnFocus: true,
                clearIncomplete: true,
                autoUnmask: true,
                onincomplete: function() {
                    alert('Card number is incomplete');
                }
            });
        });
    </script>
</body>

</html>