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

<h1>All Blogs</h1>

<?php if ($result->num_rows > 0): ?>

    <?php while ($blog = $result->fetch_assoc()): ?>

        <article>

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

        <hr>

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