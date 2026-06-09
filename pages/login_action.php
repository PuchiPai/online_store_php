<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect('login.php');
}

$email = trim($_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

if ($email === '' || $password === '') {
    die("Заполните все поля");
}

$stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Пользователь не найден");
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user["password"])) {
    die("Неверный пароль");
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["user_name"] = $user["name"];
$_SESSION["user_role"] = $user["role"];

if ($user["role"] === "admin") {
    redirect('../admin/index.php');
}

redirect('profile.php');