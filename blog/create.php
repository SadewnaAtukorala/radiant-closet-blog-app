<?php

session_start();

if(!isset($_SESSION['user_id'])){
    echo "Unauthorized access";
    exit();
}

include "../config/db.php";

$title = $_POST['title'];
$content = $_POST['content'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO blog_posts (user_id, title, content) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $title, $content);

if($stmt->execute()){
    header("Location: ../public/dashboard.php");
    exit();
} else {
    echo "Error creating post.";
}

$stmt->close();
$conn->close();

?>