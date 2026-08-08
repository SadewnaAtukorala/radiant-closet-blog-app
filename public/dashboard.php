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

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Dashboard</title>

</head>

<body>

    <h1>My Dashboard</h1>


    <p>
        Welcome to your dashboard!
    </p>


    <p>
        <a href="index.php">
            ← View All Blogs
        </a>
    </p>


    <p>
        <a href="editor.php">
            + Create New Blog
        </a>
    </p>


    <hr>


    <h2>My Blogs</h2>


    <?php if ($result->num_rows > 0): ?>


        <?php while ($blog = $result->fetch_assoc()): ?>


            <article>

                <h3>
                    <?php echo htmlspecialchars($blog['title']); ?>
                </h3>


                <p>

                    Created:
                    <?php echo htmlspecialchars($blog['created_at']); ?>

                </p>


                <?php if (!empty($blog['updated_at'])): ?>

                    <p>

                        Last updated:
                        <?php echo htmlspecialchars($blog['updated_at']); ?>

                    </p>

                <?php endif; ?>


                <p>

                    <a href="view.php?id=<?php echo $blog['id']; ?>">
                        View
                    </a>


                    |


                    <a href="editor.php?id=<?php echo $blog['id']; ?>">
                        Edit
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
                        Delete
                    </button>

                </form>


            </article>


            <hr>


        <?php endwhile; ?>


    <?php else: ?>


        <p>
            You haven't created any blogs yet.
        </p>


        <p>
            <a href="editor.php">
                Create Your First Blog
            </a>
        </p>


    <?php endif; ?>


</body>

</html>


<?php

$stmt->close();

$conn->close();

?>