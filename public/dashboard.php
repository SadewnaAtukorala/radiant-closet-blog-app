<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include "../config/db.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT *
        FROM blog_posts
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$total_posts = $result->num_rows;

include "header.php";

?>

<section class="dashboard-page">

<!-- DASHBOARD HEADER -->

<div class="dashboard-header">

    <p class="eyebrow">
        THE RADIANT CLOSET
    </p>

    <h1>
        My Dashboard
    </h1>

    <p>
        Welcome back,
        <strong>
            <?php echo htmlspecialchars($_SESSION['username']); ?>
        </strong>.
        Here's a look at your stories.
    </p>

</div>


<!-- DASHBOARD STATS -->

<div class="dashboard-stats">

    <div class="stat-card">

        <span class="stat-number">
            <?php echo $total_posts; ?>
        </span>

        <span class="stat-label">
            <?php echo ($total_posts === 1)
                ? "Blog Post"
                : "Blog Posts";
            ?>
        </span>

    </div>

</div>


<!-- QUICK ACTIONS -->

<div class="dashboard-actions">

    <a href="editor.php" class="dashboard-primary-button">
        + Create New Blog
    </a>

    <a href="index.php" class="dashboard-secondary-button">
        View All Blogs
    </a>

</div>


<!-- BLOG SECTION -->

<div class="dashboard-section">

    <div class="dashboard-section-heading">

        <div>

            <p class="eyebrow">
                YOUR STORIES
            </p>

            <h2>
                My Blogs
            </h2>

        </div>

        <span class="blog-count">
            <?php echo $total_posts; ?>
            <?php echo ($total_posts === 1)
                ? "post"
                : "posts";
            ?>
        </span>

    </div>


    <?php if ($total_posts > 0): ?>

        <div class="dashboard-blog-grid">

            <?php while ($blog = $result->fetch_assoc()): ?>

                <article class="dashboard-blog-card">


                    <!-- BLOG IMAGE -->

                    <div class="dashboard-blog-image">

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


                    <!-- BLOG CONTENT -->

                    <div class="dashboard-blog-content">


                        <?php if (!empty($blog['category'])): ?>

                            <p class="blog-category">
                                <?php echo htmlspecialchars($blog['category']); ?>
                            </p>

                        <?php endif; ?>


                        <h3>

                            <?php echo htmlspecialchars($blog['title']); ?>

                        </h3>


                        <div class="dashboard-blog-meta">

                            <span>
                                Created
                                <?php
                                echo date(
                                    "F j, Y",
                                    strtotime($blog['created_at'])
                                );
                                ?>
                            </span>


                            <?php if (!empty($blog['updated_at'])): ?>

                                <span>
                                    Updated
                                    <?php
                                    echo date(
                                        "F j, Y",
                                        strtotime($blog['updated_at'])
                                    );
                                    ?>
                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- ACTIONS -->

                        <div class="dashboard-blog-actions">

                            <a
                                href="view.php?id=<?php echo $blog['id']; ?>"
                                class="view-button"
                            >
                                View
                            </a>


                            <a
                                href="editor.php?id=<?php echo $blog['id']; ?>"
                                class="edit-button"
                            >
                                Edit
                            </a>


                            <form
                                action="../blog/delete.php"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this blog?');"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo htmlspecialchars($blog['id']); ?>"
                                >

                                <button
                                    type="submit"
                                    class="dashboard-delete-button"
                                >
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                </article>

            <?php endwhile; ?>

        </div>


    <?php else: ?>


        <!-- EMPTY STATE -->

        <div class="dashboard-empty-state">

            <h3>
                Your stories are waiting.
            </h3>

            <p>
                You haven't created any blog posts yet.
                Start sharing your thoughts, style, and inspiration.
            </p>

            <a
                href="editor.php"
                class="dashboard-primary-button"
            >
                Create Your First Blog
            </a>

        </div>


    <?php endif; ?>

</div>

</section>

<?php

include "footer.php";

$stmt->close();

$conn->close();

?>
