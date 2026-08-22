<?php

include "../config/db.php";


/* ========================================
   GET FORM DATA
======================================== */

$token = $_POST['token'] ?? "";
$password = $_POST['password'] ?? "";
$confirm_password = $_POST['confirm_password'] ?? "";


/* ========================================
   CHECK REQUIRED FIELDS
======================================== */

if ($token === "" || $password === "" || $confirm_password === "") {

    echo "Please fill in all fields.";
    exit();

}


/* ========================================
   CHECK PASSWORDS MATCH
======================================== */

if ($password !== $confirm_password) {

    echo "Passwords do not match.";
    exit();

}


/* ========================================
   CHECK PASSWORD LENGTH
======================================== */

if (strlen($password) < 8) {

    echo "Password must be at least 8 characters long.";
    exit();

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

    echo "Invalid or expired password reset link.";
    exit();

}


$reset = $result->fetch_assoc();

$user_id = $reset['user_id'];


/* ========================================
   CHECK TOKEN EXPIRY
======================================== */

if (strtotime($reset['expires_at']) < time()) {

    echo "This password reset link has expired.";
    exit();

}

$stmt->close();


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

    echo "Something went wrong. Please try again.";
    exit();

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