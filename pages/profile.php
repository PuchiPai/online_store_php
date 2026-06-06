<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$user_id = currentUserId();

$stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Пользователь не найден");
}

$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
</head>
<body>
<h2>Профиль пользователя</h2>

<p><b>ID:</b> <?= h($user["id"]) ?></p>
<p><b>Имя:</b> <?= h($user["name"]) ?></p>
<p><b>Email:</b> <?= h($user["email"]) ?></p>
<p><b>Роль:</b> <?= h($user["role"]) ?></p>

<p>
    <a href="catalog.php">Каталог</a> |
    <a href="cart.php">Корзина</a> |
    <a href="logout.php">Выйти</a>
</p>
</body>
</html>