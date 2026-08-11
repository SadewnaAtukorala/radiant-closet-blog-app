<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You must login first.";
    exit();
}

include "../config/db.php";


/* ========================================
   CHECK BLOG ID
======================================== */

if (!isset($_POST['id'])) {
    echo "Blog ID is missing.";
    exit();
}

$blog_id = $_POST['id'];
$user_id = $_SESSION['user_id'];


/* ========================================
   GET FORM DATA
======================================== */

$title = $_POST['title'];
$category = $_POST['category'];
$content = $_POST['content'];


/* ========================================
   GET CURRENT BLOG
======================================== */

$sql = "SELECT *
        FROM blog_posts
        WHERE id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $blog_id,
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    echo "Blog not found or you are not authorized to edit this blog.";
    exit();

}


$blog = $result->fetch_assoc();

$current_image = $blog['image'];

$stmt->close();


/* ========================================
   IMAGE HANDLING
======================================== */

$new_image = $current_image;


/* Check whether a new image was uploaded */

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === UPLOAD_ERR_OK
) {

    $image = $_FILES['image'];

    $allowed_types = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];


    /* Check image type */

    if (!in_array($image['type'], $allowed_types)) {

        echo "Invalid image type.";
        exit();

    }


    /* Generate unique filename */

    $extension = pathinfo(
        $image['name'],
        PATHINFO_EXTENSION
    );

    $new_image = uniqid(
        'blog_',
        true
    ) . '.' . $extension;


    /* Upload location */

    $upload_directory = "../public/uploads/blogs/";


    $upload_path = $upload_directory . $new_image;


    /* Move uploaded image */

    if (!move_uploaded_file(
        $image['tmp_name'],
        $upload_path
    )) {

        echo "Failed to upload image.";
        exit();

    }


    /* Delete old image */

    if (!empty($current_image)) {

        $old_image_path =
            $upload_directory . $current_image;

        if (file_exists($old_image_path)) {

            unlink($old_image_path);

        }

    }

}


/* ========================================
   UPDATE BLOG
======================================== */

$sql = "UPDATE blog_posts
        SET title = ?,
            category = ?,
            content = ?,
            image = ?
        WHERE id = ?
        AND user_id = ?";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "ssssii",
    $title,
    $category,
    $content,
    $new_image,
    $blog_id,
    $user_id
);


/* ========================================
   EXECUTE UPDATE
======================================== */

if ($stmt->execute()) {

    header(
        "Location: ../public/view.php?id=" . $blog_id
    );

    exit();

} else {

    echo "Error updating blog.";

}


$stmt->close();
$conn->close();

?>