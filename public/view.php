<?php

session_start();

include "../config/db.php";

if (!isset($_GET['id'])) {
    die("Blog ID is missing.");
}

$blog_id = $_GET['id'];

$sql = "SELECT blog_posts.*, users.username
        FROM blog_posts
        INNER JOIN users ON blog_posts.user_id = users.id
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

    <h1>
        <?php echo htmlspecialchars($blog['title']); ?>
    </h1>

    <p>
        By <?php echo htmlspecialchars($blog['username']); ?>
        · Published on <?php echo htmlspecialchars($blog['created_at']); ?>
    </p>

    <hr>

    <div>
        <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
    </div>

        <?php

    if (
        isset($_SESSION['user_id']) &&
        $_SESSION['user_id'] == $blog['user_id']
    ) {

    ?>

        <p>

            <a href="editor.php?id=<?php echo $blog['id']; ?>">
                Edit Blog
            </a>

        </p>


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


            <button type="submit">
                Delete Blog
            </button>

        </form>

    <?php

    }

    ?>

    <br>

    <a href="index.php">
        ← Back to All Blogs
    </a>


<?php

include "footer.php";

$stmt->close();
$conn->close();

?>