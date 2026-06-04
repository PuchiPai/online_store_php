<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "shop_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("DB connection failed");
}
?>