<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('register.php');
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
    redirect('login.php?success=1');
}

die("Ошибка регистрации");