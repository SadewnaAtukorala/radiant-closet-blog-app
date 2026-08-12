<?php

include "../config/db.php";


$username = $_POST['username'] ?? "";
$email = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";


$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


$sql = "INSERT INTO users
        (username, email, password)
        VALUES (?, ?, ?)";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "sss",
    $username,
    $email,
    $hashedPassword
);


if ($stmt->execute()) {

    header("Location: ../public/login.html?registered=1");

    exit();

}


if ($stmt->errno == 1062) {

    $message = "An account with this email already exists.";

} else {

    $message = "Something went wrong while creating your account.";

}


$stmt->close();

$conn->close();

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Registration Error | The Radiant Closet</title>

    <link
        rel="stylesheet"
        href="../public/css/style.css"
    >

</head>


<body>


<main>

    <section class="auth-page">


        <div class="auth-header">

            <p class="eyebrow">
                THE RADIANT CLOSET
            </p>

            <h1>
                Almost There
            </h1>

            <p>
                We couldn't create your account.
            </p>

        </div>


        <div class="auth-card">


            <div class="auth-error">

                <?php echo htmlspecialchars($message); ?>

            </div>


            <p class="auth-footer">

                <a href="../public/register.html">
                    ← Back to Registration
                </a>

            </p>


        </div>


    </section>

</main>


</body>

</html>