<?php

include "../config/db.php";

$sql = "SELECT blog_posts.*, users.username
        FROM blog_posts
        INNER JOIN users
        ON blog_posts.user_id = users.id
        ORDER BY blog_posts.created_at DESC";

$result = $conn->query($sql);

include "header.php";

?>

<section class="page-intro">

    <p class="eyebrow">
        FASHION • STYLE • BEAUTY
    </p>

    <h1>
        The Latest from the Closet
    </h1>

    <p>
        Discover fashion inspiration, styling ideas,
        trends, and stories from our community.
    </p>

</section>

<?php if ($result->num_rows > 0): ?>

    <?php while ($blog = $result->fetch_assoc()): ?>

        <article class="blog-card">

            <h2>
                <?php echo htmlspecialchars($blog['title']); ?>
            </h2>

            <p>
                By <?php echo htmlspecialchars($blog['username']); ?>
                ·
                <?php echo htmlspecialchars($blog['created_at']); ?>
            </p>

            <p>
                <?php
                echo nl2br(
                    htmlspecialchars(
                        substr($blog['content'], 0, 200)
                    )
                );
                ?>
            </p>

            <a href="view.php?id=<?php echo $blog['id']; ?>">
                Read More
            </a>

        </article>

    <?php endwhile; ?>

<?php else: ?>

    <p>
        No posts available.
    </p>

<?php endif; ?>

<?php

include "footer.php";

$conn->close();

?>