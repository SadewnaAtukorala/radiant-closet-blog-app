<?php

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
    die("Blog not found.");
}

$blog = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <title>
        <?php echo htmlspecialchars($blog['title']); ?>
    </title>

</head>

<body>

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

    <br>

    <a href="index.php">
        ← Back to All Blogs
    </a>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>