<?php include "../includes/db.php"; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
<h2>Регистрация</h2>

<form action="register_action.php" method="post">
    <p>
        <input type="text" name="name" placeholder="Имя" required>
    </p>

    <p>
        <input type="email" name="email" placeholder="Email" required>
    </p>

    <p>
        <input type="password" name="password" placeholder="Пароль" required>
    </p>

    <button type="submit">Зарегистрироваться</button>
</form>

<p>
    <a href="login.php">Уже есть аккаунт? Войти</a>
</p>
</body>
</html>