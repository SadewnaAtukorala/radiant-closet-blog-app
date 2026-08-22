<?php

include "../config/db.php";


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$posts_per_page = 4;

$offset = ($page - 1) * $posts_per_page;


$sql = "SELECT blog_posts.*, users.username
        FROM blog_posts
        INNER JOIN users
        ON blog_posts.user_id = users.id
        ORDER BY blog_posts.created_at DESC
        LIMIT $posts_per_page OFFSET $offset";


$result = $conn->query($sql);

include "header.php";

?>


<!-- HERO SECTION -->

<section class="hero">

    <div class="hero-image">

        <img
            src="assets/images/hero.png"
            alt="Fashion editorial"
        >

    </div>

</section>


<!-- PAGE INTRO -->

<section class="page-intro">

    <p class="eyebrow">
        FASHION • STYLE • BEAUTY
    </p>

    <h1>
        The Latest From The Closet
    </h1>

    <p>
        Discover fashion inspiration, styling ideas,
        trends, and stories from The Radiant Closet.
    </p>

</section>


<!-- BLOG POSTS SECTION -->

<section class="posts-section">


    <div class="section-heading">

        <p class="eyebrow">
            FROM THE CLOSET
        </p>

        <h2>
            Latest Posts
        </h2>

    </div>


    <?php if ($result->num_rows > 0): ?>


        <div class="blog-grid">


            <?php while ($blog = $result->fetch_assoc()): ?>


                <article class="blog-card">


                    <!-- BLOG IMAGE -->

                    <div class="blog-card-image">

                        <?php if (!empty($blog['image'])): ?>

                            <img
                                src="uploads/blogs/<?php echo htmlspecialchars($blog['image']); ?>"
                                alt="<?php echo htmlspecialchars($blog['title']); ?>"
                                loading="lazy"
                            >

                        <?php else: ?>

                        <img
                            src="assets/images/default-blog.jpg"
                            alt="Fashion blog"
                            loading="lazy"
                        >

                        <?php endif; ?>

                    </div>


                    <!-- BLOG DETAILS -->

                    <div class="blog-card-content">


                        <!-- CATEGORY -->

                        <p class="blog-category">

                            <?php echo htmlspecialchars($blog['category']); ?>

                        </p>


                        <!-- TITLE -->

                        <h2>

                            <?php echo htmlspecialchars($blog['title']); ?>

                        </h2>


                        <!-- AUTHOR + DATE -->

                        <p class="blog-meta">

                            <em>
                                By <?php echo htmlspecialchars($blog['username']); ?>
                            </em>

                            <span> · </span>

                            <em>
                                <?php
                                    echo date(
                                        "F j, Y",
                                        strtotime($blog['created_at'])
                                    );
                                ?>
</em>

                        </p>


                        <!-- EXCERPT -->

                        <p class="blog-excerpt">

                            <?php

                            echo htmlspecialchars(
                                substr($blog['content'], 0, 160)
                            );

                            ?>

                            <?php if (strlen($blog['content']) > 160): ?>

                                ...

                            <?php endif; ?>

                        </p>


                        <!-- READ MORE -->

                        <a
                            class="read-more"
                            href="view.php?id=<?php echo $blog['id']; ?>"
                        >
                            Read More →
                        </a>


                    </div>


                </article>


            <?php endwhile; ?>


        </div>


    <?php else: ?>

    <div class="empty-state">

        <p class="empty-state-title">
            No stories yet
        </p>

        <p>
            Your next fashion story could be the first.
        </p>

    </div>

<?php endif; ?>


<?php if ($result->num_rows === $posts_per_page): ?>

    <div class="load-more-container">

        <a
            href="index.php?page=<?php echo $page + 1; ?>"
            class="load-more"
        >
            Load More
        </a>

    </div>

<?php endif; ?>


</section>


<?php

include "footer.php";

$conn->close();

?>