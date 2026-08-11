<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>The Radiant Closet</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header>

    <nav>

        <a href="index.php" class="logo-link">
        <img
            src="assets/images/logo.png"
            alt="The Radiant Closet"
            class="site-logo"
        >
</a>

        <div>

            <a href="index.php">
                Home
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>

                <a href="dashboard.php">
                    Dashboard
                </a>

                <a href="editor.php">
                    Create Blog
                </a>

                <a href="../auth/logout.php">
                    Logout
                </a>

            <?php else: ?>

                <a href="login.html">
                    Login
                </a>

                <a href="register.html">
                    Register
                </a>

            <?php endif; ?>

        </div>

    </nav>

</header>

<main></main>