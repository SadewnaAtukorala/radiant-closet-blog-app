<?php

include "../config/db.php";


/* ========================================
   GET FORM DATA
======================================== */

$token = $_POST['token'] ?? "";
$password = $_POST['password'] ?? "";
$confirm_password = $_POST['confirm_password'] ?? "";


/* ========================================
   FUNCTION TO DISPLAY STYLED ERROR
======================================== */

function showResetError($message)
{
    echo '
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Password Reset | The Radiant Closet</title>

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
                Reset Your Password
            </h1>

            <p>
                There was a problem resetting your password.
            </p>

        </div>


        <div class="auth-card">

            <div class="auth-error">

                ' . htmlspecialchars($message) . '

            </div>


            <p class="auth-footer">

                <a href="javascript:history.back()">
                    ← Go Back
                </a>

            </p>

        </div>

    </section>

</main>

</body>

</html>
';

    exit();
}


/* ========================================
   CHECK REQUIRED FIELDS
======================================== */

if (
    $token === "" ||
    $password === "" ||
    $confirm_password === ""
) {

    showResetError("Please fill in all fields.");

}


/* ========================================
   CHECK PASSWORDS MATCH
======================================== */

if ($password !== $confirm_password) {

    showResetError("Passwords do not match.");

}


/* ========================================
   CHECK PASSWORD LENGTH
======================================== */

if (strlen($password) < 8) {

    showResetError(
        "Password must be at least 8 characters long."
    );

}


/* ========================================
   FIND RESET TOKEN
======================================== */

$sql = "SELECT user_id, expires_at
        FROM password_resets
        WHERE token = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $token
);

$stmt->execute();

$result = $stmt->get_result();


/* ========================================
   CHECK TOKEN EXISTS
======================================== */

if ($result->num_rows === 0) {

    showResetError(
        "Invalid or expired password reset link."
    );

}


$reset = $result->fetch_assoc();

$user_id = $reset['user_id'];


/* ========================================
   CHECK TOKEN EXPIRY
======================================== */

if (strtotime($reset['expires_at']) < time()) {

    showResetError(
        "This password reset link has expired."
    );

}

$stmt->close();


/* ========================================
   GET CURRENT PASSWORD
======================================== */

$sql = "SELECT password
        FROM users
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    showResetError(
        "Unable to find your account."
    );

}


$user = $result->fetch_assoc();

$current_password_hash = $user['password'];

$stmt->close();


/* ========================================
   CHECK OLD PASSWORD
======================================== */

if (password_verify($password, $current_password_hash)) {

    showResetError(
        "Your new password must be different from your current password."
    );

}


/* ========================================
   HASH NEW PASSWORD
======================================== */

$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/* ========================================
   UPDATE USER PASSWORD
======================================== */

$sql = "UPDATE users
        SET password = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "si",
    $hashed_password,
    $user_id
);


if (!$stmt->execute()) {

    showResetError(
        "Something went wrong. Please try again."
    );

}

$stmt->close();


/* ========================================
   DELETE USED RESET TOKEN
======================================== */

$sql = "DELETE FROM password_resets
        WHERE token = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $token
);

$stmt->execute();

$stmt->close();


/* ========================================
   CLOSE DATABASE CONNECTION
======================================== */

$conn->close();


/* ========================================
   REDIRECT TO LOGIN
======================================== */

header("Location: ../public/login.html?reset=1");

exit();

?>