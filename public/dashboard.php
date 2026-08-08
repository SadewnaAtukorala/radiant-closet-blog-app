<?php

session_start();

if(!isset($_SESSION['user_id'])){
    echo "You must login first.";
    exit();
}

?>

<h2>Welcome <?php echo $_SESSION['username']; ?>!</h2>

<p>You are logged in.</p>

<a href="../auth/logout.php">Logout</a>

<a href="editor.php">Create New Post</a>

<a href="index.php">View All Blogs</a>