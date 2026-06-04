<?php
include "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}

$name = trim($_POST["name"] ?? '');
$email = trim($_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

if ($name === '' || $email === '' || $password === '') {
    die("Заполните все поля");
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("Пользователь с таким email уже существует");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
$stmt->bind_param("sss", $name, $email, $hash);

if ($stmt->execute()) {
    header("Location: login.php?success=1");
    exit;
}

die("Ошибка регистрации");