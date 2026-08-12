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
$category = "";
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

<section class="editor-page">
    <?php if (isset($_GET['error']) && $_GET['error'] === 'image'): ?>

    <div class="form-error">
        Please select a cover image for your blog.
    </div>

<?php endif; ?>

    <div class="editor-header">

        <p class="eyebrow">
            THE RADIANT CLOSET
        </p>

        <h1>
            <?php
            echo $edit_mode
                ? "Edit Your Story"
                : "Create Your Story";
            ?>
        </h1>

        <p>
            <?php
            echo $edit_mode
                ? "Make changes to your blog post and keep your story fresh."
                : "Share your thoughts, style, and inspiration with your readers.";
            ?>
        </p>

    </div>


    <?php if ($edit_mode): ?>

        <form
            action="../blog/update.php"
            method="POST"
            enctype="multipart/form-data"
            class="blog-editor-form"
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
            class="blog-editor-form"
        >

    <?php endif; ?>


        <!-- BLOG TITLE -->

        <div class="form-group">

            <label for="title">
                Blog Title
            </label>

            <input
                type="text"
                id="title"
                name="title"
                value="<?php echo htmlspecialchars($title); ?>"
                placeholder="Enter your blog title..."
                required
            >

        </div>


        <!-- CATEGORY -->

        <div class="form-group">

            <label for="category">
                Category
            </label>

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

        </div>


        <!-- BLOG IMAGE -->

        <div class="form-group">

            <label for="image">
                Cover Image
            </label>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg,image/png,image/webp"
            >

            <small>
                Upload a JPG, PNG, or WebP image for your blog.
            </small>


            <?php if ($edit_mode && !empty($current_image)): ?>

                <div class="current-image">

                    <p>
                        Current image
                    </p>

                    <img
                        src="uploads/blogs/<?php echo htmlspecialchars($current_image); ?>"
                        alt="Current blog image"
                    >

                </div>

            <?php endif; ?>

        </div>


        <!-- BLOG CONTENT -->

        <div class="form-group">

            <label for="content">
                Your Story
            </label>

            <textarea
                id="content"
                name="content"
                rows="15"
                placeholder="Start writing your story..."
                required
            ><?php echo htmlspecialchars($content); ?></textarea>

        </div>


        <!-- ACTIONS -->

        <div class="editor-actions">

            <button
                type="submit"
                class="publish-button"
            >

                <?php
                echo $edit_mode
                    ? "Update Blog"
                    : "Publish Blog";
                ?>

            </button>


            <a
                href="<?php echo $edit_mode
                    ? 'view.php?id=' . htmlspecialchars($blog_id)
                    : 'index.php';
                ?>"
                class="cancel-button"
            >
                Cancel
            </a>

        </div>


    </form>

</section>


<?php

include "footer.php";

$conn->close();

?>
