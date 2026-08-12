<?php

$message = $_GET['message'] ?? "Something went wrong.";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Error | The Radiant Closet</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<main>

    <section class="error-page">

        <p class="eyebrow">
            THE RADIANT CLOSET
        </p>

        <div class="error-card">

            <h1>
                Something went wrong
            </h1>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

            <a
                href="index.php"
                class="error-button"
            >
                ← Back to Blogs
            </a>

        </div>

    </section>

</main>

</body>

</html>