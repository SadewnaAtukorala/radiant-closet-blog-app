<?php

include "../config/db.php";


$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];



$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);



$sql = "INSERT INTO users
(username,email,password)
VALUES
(?,?,?)";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "sss",
    $username,
    $email,
    $hashedPassword
);


if($stmt->execute()){

    echo "Registration successful!";

}
else{

    if($stmt->errno == 1062){

        echo "Email already exists.";

    }
    else{

        echo "Error: ".$stmt->error;

    }

}


$stmt->close();
$conn->close();

?>