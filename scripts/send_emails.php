<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch the current rate from the rate API
$rate = file_get_contents('http://localhost:8000/api/rate');
if ($rate === false) {
    die('Error fetching the current rate');
}
$rateBuy = json_decode($rate, true)['rateBuy'];
$rateSell = json_decode($rate, true)['rateSell'];

// Connect to the database
$pdo = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');

// Fetch all subscribed emails
$stmt = $pdo->query('SELECT email FROM subscriptions');
$emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Set up PHPMailer
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server to send through
    $mail->SMTPAuth   = true;
    $mail->Username   = 'andrey5555@gmail.com';  // SMTP username
    $mail->Password   = 'tngzxejpngcewfcr';    // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender info
    $mail->setFrom('no-reply@rateapp.com', 'Rate Application');
    $mail->AddReplyTo('no-reply@rateapp.com', 'Rate Application');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Daily USD to UAH Rate';
    $mail->Body    = 'The current rate of USD to UAH<br><br>Buy rate is ' . $rateBuy . '<br>Sell rate is ' . $rateSell;

    // Send email to all subscribers
    foreach ($emails as $email) {
        $mail->addAddress($email);
        $mail->send();
        $mail->clearAddresses();
    }

    echo 'Emails have been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}