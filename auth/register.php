<?php

include "../config/db.php";


$username = trim($_POST['username'] ?? "");
$email = trim($_POST['email'] ?? "");
$password = $_POST['password'] ?? "";


/*
|--------------------------------------------------------------------------
| Check whether email already exists
|--------------------------------------------------------------------------
*/

$check_sql = "SELECT id FROM users WHERE email = ?";

$check_stmt = $conn->prepare($check_sql);

$check_stmt->bind_param(
    "s",
    $email
);

$check_stmt->execute();

$check_result = $check_stmt->get_result();


if ($check_result->num_rows > 0) {

    $message = "An account with this email already exists.";

    $check_stmt->close();
    $conn->close();

} else {

    /*
    |--------------------------------------------------------------------------
    | Create hashed password
    |--------------------------------------------------------------------------
    */

    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    /*
    |--------------------------------------------------------------------------
    | Insert new user
    |--------------------------------------------------------------------------
    */

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

        $stmt->close();
        $conn->close();

        header("Location: ../public/login.html?registered=1");
        exit();

    } else {

        $message = "Something went wrong while creating your account.";

        $stmt->close();
        $conn->close();

    }

}

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