<?php

session_start();


if (!isset($_SESSION['user_id'])) {

    echo "Unauthorized access.";
    exit();

}


include "../config/db.php";


/*
|--------------------------------------------------------------------------
| Get form data
|--------------------------------------------------------------------------
*/

$title = trim($_POST['title'] ?? "");
$category = trim($_POST['category'] ?? "");
$content = trim($_POST['content'] ?? "");


/*
|--------------------------------------------------------------------------
| Check required fields
|--------------------------------------------------------------------------
*/

if ($title === "" || $category === "" || $content === "") {

    echo "Please fill in all required fields.";
    exit();

}


/*
|--------------------------------------------------------------------------
| Check image
|--------------------------------------------------------------------------
*/

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {

    echo "Please select a blog image.";
    exit();

}


$image = $_FILES['image'];


/*
|--------------------------------------------------------------------------
| Validate image type
|--------------------------------------------------------------------------
*/

$allowed_types = [
    "image/jpeg",
    "image/png",
    "image/webp"
];


if (!in_array($image['type'], $allowed_types)) {

    echo "Only JPG, PNG, and WEBP images are allowed.";
    exit();

}


/*
|--------------------------------------------------------------------------
| Validate image size
|--------------------------------------------------------------------------
*/

$max_size = 5 * 1024 * 1024; // 5 MB


if ($image['size'] > $max_size) {

    echo "Image must be smaller than 5 MB.";
    exit();

}


/*
|--------------------------------------------------------------------------
| Create unique filename
|--------------------------------------------------------------------------
*/

$extension = pathinfo(
    $image['name'],
    PATHINFO_EXTENSION
);


$new_filename = uniqid(
    "blog_",
    true
) . "." . strtolower($extension);


/*
|--------------------------------------------------------------------------
| Image upload location
|--------------------------------------------------------------------------
*/

$upload_directory = "../public/uploads/blogs/";


$upload_path = $upload_directory . $new_filename;


/*
|--------------------------------------------------------------------------
| Move uploaded image
|--------------------------------------------------------------------------
*/

if (!move_uploaded_file($image['tmp_name'], $upload_path)) {

    echo "Failed to upload image.";
    exit();

}


/*
|--------------------------------------------------------------------------
| Get logged-in user
|--------------------------------------------------------------------------
*/

$user_id = $_SESSION['user_id'];


/*
|--------------------------------------------------------------------------
| Insert blog into database
|--------------------------------------------------------------------------
*/

$sql = "INSERT INTO blog_posts
        (user_id, title, content, category, image)
        VALUES (?, ?, ?, ?, ?)";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "issss",
    $user_id,
    $title,
    $content,
    $category,
    $new_filename
);


if ($stmt->execute()) {

    header("Location: ../public/index.php");
    exit();

} else {

    echo "Error creating blog.";

}


/*
|--------------------------------------------------------------------------
| Close resources
|--------------------------------------------------------------------------
*/

$stmt->close();
$conn->close();

?>