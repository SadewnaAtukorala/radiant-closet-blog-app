<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You must login first.";
    exit();
}

include "../config/db.php";


$edit_mode = false;
$blog_id = "";
$title = "";
$content = "";


if (isset($_GET['id'])) {

    $edit_mode = true;

    $blog_id = $_GET['id'];

    $user_id = $_SESSION['user_id'];


    $sql = "SELECT * FROM blog_posts
            WHERE id = ? AND user_id = ?";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $blog_id,
        $user_id
    );

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows === 0) {

        echo "Blog not found or you are not authorized to edit this blog.";
        exit();

    }


    $blog = $result->fetch_assoc();

    $title = $blog['title'];
    $content = $blog['content'];


    $stmt->close();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>
        <?php
        echo $edit_mode
            ? "Edit Blog"
            : "Create Blog";
        ?>
    </title>

</head>

<body>

    <h1>
        <?php
        echo $edit_mode
            ? "Edit Blog"
            : "Create New Blog";
        ?>
    </h1>


    <?php if ($edit_mode): ?>

        <form action="../blog/update.php" method="POST">

            <input
                type="hidden"
                name="id"
                value="<?php echo htmlspecialchars($blog_id); ?>"
            >

    <?php else: ?>

        <form action="../blog/create.php" method="POST">

    <?php endif; ?>


        <label>Title:</label>
        <br>

        <input
            type="text"
            name="title"
            value="<?php echo htmlspecialchars($title); ?>"
            required
        >

        <br><br>


        <label>Content:</label>
        <br>

        <textarea
            name="content"
            rows="10"
            cols="60"
            required
        ><?php echo htmlspecialchars($content); ?></textarea>

        <br><br>


        <button type="submit">

            <?php
            echo $edit_mode
                ? "Update Blog"
                : "Publish Blog";
            ?>

        </button>


    </form>


    <br>

    <a href="index.php">
        Cancel
    </a>

</body>

</html>

<?php

$conn->close();

?>