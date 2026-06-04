<?php include "../includes/db.php"; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body>
<h2>Вход</h2>

<?php if (isset($_GET['success'])): ?>
    <p>Регистрация успешна. Теперь войдите в аккаунт.</p>
<?php endif; ?>

<form action="login_action.php" method="post">
    <p>
        <input type="email" name="email" placeholder="Email" required>
    </p>

    <p>
        <input type="password" name="password" placeholder="Пароль" required>
    </p>

    <button type="submit">Войти</button>
</form>

<p>
    <a href="register.php">Нет аккаунта? Регистрация</a>
</p>
</body>
</html>