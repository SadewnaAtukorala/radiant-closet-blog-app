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
$user_id = $_SESSION['user_id'];


$sql = "DELETE FROM blog_posts
        WHERE id = ? AND user_id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $blog_id,
    $user_id
);


if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        header("Location: ../public/dashboard.php");
        exit();

    } else {

        echo "Blog not found or you are not authorized to delete this blog.";

    }

} else {

    echo "Error deleting blog.";

}


$stmt->close();
$conn->close();

?>