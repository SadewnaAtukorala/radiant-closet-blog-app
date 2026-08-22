<?php

session_start();

include "../config/db.php";


$email = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";


$sql = "SELECT * FROM users WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();


    if (password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];

        $_SESSION['username'] = $user['username'];


        header("Location: ../public/dashboard.php");

        exit();

    }

}


/*
    We intentionally use one generic message
    instead of revealing whether the email
    exists in the database.
*/

$error_message = "Invalid email or password.";


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

    <title>Login Error | The Radiant Closet</title>

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
                Welcome Back
            </h1>

            <p>
                Sign in to continue sharing your stories.
            </p>

        </div>


        <div class="auth-card">


            <!-- ERROR -->

            <div class="auth-error">

                <?php echo htmlspecialchars($error_message); ?>

            </div>


            <form
                action="login.php"
                method="POST"
                class="auth-form"
            >


                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($email); ?>"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>

                <p class="forgot-password-link">
                    <a href="../forgot-password.html">
                        Forgot your password?
                    </a>
                </p>

                <button
                    type="submit"
                    class="auth-button"
                >
                    Try Again
                </button>


            </form>


            <p class="auth-footer">

                Don't have an account?

                <a href="../public/register.html">
                    Create one
                </a>

            </p>


        </div>


    </section>

</main>


</body>

</html>