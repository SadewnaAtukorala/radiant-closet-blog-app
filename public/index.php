<?php
include "../config/db.php";

$sql = "SELECT * FROM blog_posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home - Blogs</title>
</head>

<body>

<h1>All Blogs</h1>

<?php

if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){
?>

        <div style="border:1px solid black; padding:10px; margin:10px;">

            <h3><?php echo $row['title']; ?></h3>

            <p>
                <?php echo substr($row['content'], 0, 100); ?>...
            </p>

            <a href="view.php?id=<?php echo $row['id']; ?>">
                Read More
            </a>

        </div>

<?php
    }

}else{
    echo "No posts available.";
}

$conn->close();
?>

</body>
</html>