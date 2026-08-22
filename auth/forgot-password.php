<?php 
 
include "../config/db.php"; 
 
$env = parse_ini_file("../.env"); 
 
 
$email = trim($_POST['email'] ?? ""); 
 
 
/* ======================================== 
   CHECK EMAIL 
======================================== */ 
 
if ($email === "") { 
 
    echo "Please enter your email."; 
    exit(); 
 
} 
 
 
/* ======================================== 
   FIND USER 
======================================== */ 
 
$sql = "SELECT id 
        FROM users 
        WHERE email = ?"; 
 
$stmt = $conn->prepare($sql); 
 
$stmt->bind_param( 
    "s", 
    $email 
); 
 
$stmt->execute(); 
 
$result = $stmt->get_result(); 
 
 
/* ======================================== 
   CHECK USER 
======================================== */ 
 
if ($result->num_rows === 0) { 
 
    echo "No account was found with that email address."; 
    exit(); 
 
} 
 
 
$user = $result->fetch_assoc(); 
 
$user_id = $user['id']; 
 
$stmt->close(); 
 
 
/* ======================================== 
   GENERATE RESET TOKEN 
======================================== */ 
 
$token = bin2hex(random_bytes(32)); 
 
 
/* ======================================== 
   SET EXPIRY 
   Token valid for 1 hour 
======================================== */ 
 
$expires_at = date( 
    "Y-m-d H:i:s", 
    time() + 3600 
); 
 
 
/* ======================================== 
   DELETE OLD TOKENS 
======================================== */ 
 
$sql = "DELETE FROM password_resets 
        WHERE user_id = ?"; 
 
$stmt = $conn->prepare($sql); 
 
$stmt->bind_param( 
    "i", 
    $user_id 
); 
 
$stmt->execute(); 
 
$stmt->close(); 
 
 
/* ======================================== 
   SAVE NEW TOKEN 
======================================== */ 
 
$sql = "INSERT INTO password_resets 
        (user_id, token, expires_at) 
        VALUES (?, ?, ?)"; 
 
$stmt = $conn->prepare($sql); 
 
$stmt->bind_param( 
    "iss", 
    $user_id, 
    $token, 
    $expires_at 
); 
 
 
if (!$stmt->execute()) { 
 
    echo "Something went wrong. Please try again."; 
    exit(); 
 
} 
 
 
/* ======================================== 
   CREATE RESET LINK 
======================================== */ 
 
$reset_link = 
    $env['APP_URL'] . "/public/reset-password.php?token=" . $token; 


/* ========================================
   SEND RESET EMAIL
======================================== */

require_once "../PHPMailer/src/Exception.php";
require_once "../PHPMailer/src/PHPMailer.php";
require_once "../PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$mail = new PHPMailer(true);


try {

    /* ========================================
       SMTP SETTINGS
    ======================================== */

    $mail->isSMTP();

    $mail->Host = $env['MAIL_HOST'];
    $mail->SMTPAuth = true;

    $mail->Username = $env['MAIL_USERNAME'];
    $mail->Password = $env['MAIL_PASSWORD'];

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $env['MAIL_PORT'];


    /* ========================================
       SENDER
    ======================================== */

    $mail->setFrom(
        $env['MAIL_FROM'],
        $env['MAIL_FROM_NAME']
    );


    /* ========================================
       RECIPIENT
    ======================================== */

    $mail->addAddress($email);


    /* ========================================
       EMAIL CONTENT
    ======================================== */

    $mail->isHTML(true);

    $mail->Subject = "Password Reset - The Radiant Closet";

    $mail->Body = "
        <h2>Password Reset</h2>

        <p>Hello,</p>

        <p>
            We received a request to reset the password
            for your The Radiant Closet account.
        </p>

        <p>
            Click the button below to create a new password:
        </p>

        <p>
            <a href='$reset_link'
               style='
                   display:inline-block;
                   padding:12px 20px;
                   background:#d9a6a6;
                   color:white;
                   text-decoration:none;
                   border-radius:5px;
               '>
                Reset Password
            </a>
        </p>

        <p>
            This link will expire in 1 hour.
        </p>

        <p>
            If you did not request a password reset,
            you can safely ignore this email.
        </p>

        <p>
            The Radiant Closet
        </p>
    ";

    $mail->AltBody =
        "Password Reset\n\n"
        . "Use the following link to reset your password:\n\n"
        . $reset_link
        . "\n\nThis link will expire in 1 hour.";


    /* ========================================
       SEND EMAIL
    ======================================== */

    $mail->send();


    echo '
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Check Your Email | The Radiant Closet</title>

    <link rel="stylesheet" href="../public/css/style.css">

</head>


<body>

<main>

    <section class="auth-page">

        <div class="auth-header">

            <p class="eyebrow">
                THE RADIANT CLOSET
            </p>

            <h1>
                Check Your Email
            </h1>

            <p>
                We have sent you a password reset link.
            </p>

        </div>


        <div class="auth-card reset-success">

            <div class="reset-success-icon">
                ✓
            </div>

            <h2>
                Reset Link Sent
            </h2>

            <p>
                A password reset link has been sent to your email address.
                Please check your inbox and follow the link to create a new password.
            </p>

            <p>
                The link will expire in 1 hour.
            </p>

            <a href="../public/login.html" class="auth-button">
                Back to Login
            </a>

        </div>

    </section>

</main>

</body>

</html>
';
}
catch (Exception $e) {

    echo "Unable to send the password reset email. Please try again later.";

}


$stmt->close(); 
 
$conn->close(); 
 
?>