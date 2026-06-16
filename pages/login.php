<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$body_class = 'auth-page'; // задаём класс для body
$pageTitle = 'Вход — TechStore';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <h1 class="auth-title">Вход</h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="auth-message"> Регистрация успешна. Теперь войдите.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="auth-message error">
            
            <?php
                $error = $_GET['error'];
                if ($error === 'empty') echo 'Заполните все поля';
                elseif ($error === 'not_found') echo 'Пользователь не найден';
                elseif ($error === 'wrong_password') echo 'Неверный пароль';
                else echo 'Ошибка входа';
            ?>
        </div>
    <?php endif; ?>

    <form action="login_action.php" method="post" class="auth-form">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit" class="auth-btn">Войти</button>
    </form>

    <div class="auth-footer">
        Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>