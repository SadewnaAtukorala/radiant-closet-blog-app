<?php
session_start();

if(!isset($_SESSION['user_id'])){
    echo "You must login first.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>

<body>

<h2>Create New Post</h2>

<form action="../blog/create.php" method="POST">

    <label>Title:</label><br>
    <input type="text" name="title" required>

    <br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="5" cols="40" required></textarea>

    <br><br>

    <button type="submit">Publish</button>

</form>

</body>
</html>