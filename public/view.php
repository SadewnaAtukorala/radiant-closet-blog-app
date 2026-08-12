<?php

session_start();

include "../config/db.php";


if (!isset($_GET['id'])) {
    die("Blog ID is missing.");
}


$blog_id = $_GET['id'];


$sql = "SELECT blog_posts.*, users.username
        FROM blog_posts
        INNER JOIN users
        ON blog_posts.user_id = users.id
        WHERE blog_posts.id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $blog_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    echo "Blog not found.";
    exit();

}


$blog = $result->fetch_assoc();


include "header.php";

?>


<article class="single-blog">


    <!-- Blog Header -->

    <header class="single-blog-header">


        <!-- Category -->

        <p class="single-blog-category">

            <?php echo htmlspecialchars($blog['category']); ?>

        </p>


        <!-- Title -->

        <h1 class="single-blog-title">

            <?php echo htmlspecialchars($blog['title']); ?>

        </h1>


        <!-- Author + Date -->

        <p class="single-blog-meta">

            By
            <span>
                <?php echo htmlspecialchars($blog['username']); ?>
            </span>

            ·

            <?php
            echo date(
                "F j, Y",
                strtotime($blog['created_at'])
            );
            ?>

        </p>


    </header>


    <div class="single-blog-image">

    <?php if (!empty($blog['image'])): ?>

        <img
            src="uploads/blogs/<?php echo htmlspecialchars($blog['image']); ?>"
            alt="<?php echo htmlspecialchars($blog['title']); ?>"
        >

    <?php else: ?>

        <img
            src="assets/images/default-blog.jpg"
            alt="The Radiant Closet"
        >

    <?php endif; ?>

</div>

    <!-- Blog Content -->

    <div class="single-blog-content">

        <?php echo nl2br(htmlspecialchars($blog['content'])); ?>

    </div>


    <!-- Owner Actions -->

    <?php

    if (
        isset($_SESSION['user_id']) &&
        $_SESSION['user_id'] == $blog['user_id']
    ):

    ?>


        <div class="single-blog-actions">


            <a
                href="editor.php?id=<?php echo $blog['id']; ?>"
                class="edit-button"
            >
                Edit Blog
            </a>


            <form
    action="../blog/delete.php"
    method="POST"
    class="delete-form"
>


                <input
                    type="hidden"
                    name="id"
                    value="<?php echo htmlspecialchars($blog['id']); ?>"
                >


                <button
    type="button"
    class="delete-button delete-trigger"
>
    Delete Blog
</button>


            </form>


        </div>


    <?php endif; ?>


    <!-- Back -->

    <div class="single-blog-back">

        <a href="index.php">

            ← Back to All Blogs

        </a>

    </div>


</article>


<?php

include "footer.php";

$stmt->close();

$conn->close();

?>