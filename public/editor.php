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
$category = "";
$content = "";
$current_image = "";


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
    $category = $blog['category'];
    $content = $blog['content'];
    $current_image = $blog['image'];


    $stmt->close();
}


include "header.php";

?>


<h1>

    <?php

    echo $edit_mode
        ? "Edit Blog"
        : "Create New Blog";

    ?>

</h1>


<?php if ($edit_mode): ?>

    <form
        action="../blog/update.php"
        method="POST"
        enctype="multipart/form-data"
    >

        <input
            type="hidden"
            name="id"
            value="<?php echo htmlspecialchars($blog_id); ?>"
        >

<?php else: ?>

    <form
        action="../blog/create.php"
        method="POST"
        enctype="multipart/form-data"
    >

<?php endif; ?>


    <!-- BLOG TITLE -->

    <label for="title">
        Title:
    </label>

    <br>

    <input
        type="text"
        id="title"
        name="title"
        value="<?php echo htmlspecialchars($title); ?>"
        required
    >

    <br><br>


    <!-- CATEGORY -->

    <label for="category">
        Category:
    </label>

    <br>

    <select
        id="category"
        name="category"
        required
    >

        <option value="">
            Select a category
        </option>

        <option
            value="Fashion"
            <?php echo ($category === "Fashion") ? "selected" : ""; ?>
        >
            Fashion
        </option>

        <option
            value="Style"
            <?php echo ($category === "Style") ? "selected" : ""; ?>
        >
            Style
        </option>

        <option
            value="Beauty"
            <?php echo ($category === "Beauty") ? "selected" : ""; ?>
        >
            Beauty
        </option>

        <option
            value="Lifestyle"
            <?php echo ($category === "Lifestyle") ? "selected" : ""; ?>
        >
            Lifestyle
        </option>

        <option
            value="Trends"
            <?php echo ($category === "Trends") ? "selected" : ""; ?>
        >
            Trends
        </option>

    </select>

    <br><br>


    <!-- BLOG IMAGE -->

    <label for="image">
        Blog Image:
    </label>

    <br>

    <input
        type="file"
        id="image"
        name="image"
        accept="image/jpeg,image/png,image/webp"
    >

    <br>


    <?php if ($edit_mode && !empty($current_image)): ?>

        <p>
            Current image:
        </p>

        <img
            src="uploads/blogs/<?php echo htmlspecialchars($current_image); ?>"
            alt="Current blog image"
            width="200"
        >

        <br><br>

    <?php endif; ?>


    <!-- BLOG CONTENT -->

    <label for="content">
        Content:
    </label>

    <br>

    <textarea
        id="content"
        name="content"
        rows="10"
        cols="60"
        required
    ><?php echo htmlspecialchars($content); ?></textarea>

    <br><br>


    <!-- SUBMIT BUTTON -->

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


<?php

include "footer.php";

$conn->close();

?>