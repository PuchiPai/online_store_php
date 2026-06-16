<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$body_class = 'auth-page';
$pageTitle = 'Регистрация — TechStore';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <h1 class="auth-title">Регистрация</h1>
    <form action="register_action.php" method="post" class="auth-form">
        <input type="text" name="name" placeholder="Имя" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit" class="auth-btn">Зарегистрироваться</button>
    </form>
    <div class="auth-footer">
        Уже есть аккаунт? <a href="login.php">Войти</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>