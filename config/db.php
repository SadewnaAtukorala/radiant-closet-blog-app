<?php

$env = parse_ini_file(__DIR__ . '/../.env');


$host = $env['DB_HOST'];
$port = $env['DB_PORT'];
$username = $env['DB_USER'];
$password = $env['DB_PASSWORD'];
$database = $env['DB_NAME'];


$conn = new mysqli(
    $host,
    $username,
    $password,
    $database,
    $port
);


if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

?>