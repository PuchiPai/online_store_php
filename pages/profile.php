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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет — TechStore</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">  <!-- тот же класс, что на логине – абстрактный фон -->
    <div class="profile-container">
        <h1 class="profile-title">Личный кабинет</h1>
        
        <div class="profile-card">
            <div class="profile-avatar">
                <span class="avatar-placeholder">👤</span>
            </div>
            <div class="profile-info">
                <div class="info-row">
                    <span class="info-label">Имя</span>
                    <span class="info-value"><?= h($user["name"]) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= h($user["email"]) ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Роль</span>
                    <span class="info-value role-<?= h($user["role"]) ?>"><?= ($user["role"] === 'admin' ? 'Администратор' : 'Покупатель') ?></span>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="catalog.php" class="profile-link">Каталог</a>
            <a href="cart.php" class="profile-link">Корзина</a>
            <a href="orders.php" class="profile-link">Мои заказы</a>
            <a href="logout.php" class="profile-link logout">Выйти</a>
        </div>
    </div>
</body>
</html>