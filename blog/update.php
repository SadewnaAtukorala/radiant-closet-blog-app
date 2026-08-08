<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You must login first.";
    exit();
}

include "../config/db.php";

if (!isset($_POST['id'])) {
    echo "Blog ID is missing.";
    exit();
}

$blog_id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

$user_id = $_SESSION['user_id'];


$sql = "UPDATE blog_posts
        SET title = ?, content = ?
        WHERE id = ? AND user_id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssii",
    $title,
    $content,
    $blog_id,
    $user_id
);


if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        header("Location: ../public/view.php?id=" . $blog_id);
        exit();

    } else {

        echo "Blog not found or you are not authorized to edit this blog.";

    }

} else {

    echo "Error updating blog.";

}


$stmt->close();
$conn->close();

?>