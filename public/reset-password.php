<?php

include "../config/db.php";


$token = $_GET['token'] ?? "";


/* ========================================
   CHECK TOKEN
======================================== */

if ($token === "") {

    echo "Invalid password reset link.";
    exit();

}


/* ========================================
   FIND TOKEN
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


/* ========================================
   CHECK EXPIRY
======================================== */

if (strtotime($reset['expires_at']) < time()) {

    echo "This password reset link has expired.";
    exit();

}


$user_id = $reset['user_id'];

$stmt->close();

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reset Password | The Radiant Closet</title>

    <link
        rel="stylesheet"
        href="css/style.css"
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
                Reset Your Password
            </h1>

            <p>
                Create a new password for your account.
            </p>

        </div>


        <div class="auth-card">


            <form
                action="../auth/reset-password.php"
                method="POST"
                class="auth-form"
            >


                <input
                    type="hidden"
                    name="token"
                    value="<?php echo htmlspecialchars($token); ?>"
                >


                <div class="form-group">

                    <label for="password">
                        New Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your new password"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="confirm_password">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="Confirm your new password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="auth-button"
                >
                    Reset Password
                </button>


            </form>


            <p class="auth-footer">

                <a href="login.html">
                    ← Back to Login
                </a>

            </p>


        </div>


    </section>


</main>


</body>

</html>