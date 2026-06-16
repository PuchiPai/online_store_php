<?php
http_response_code(404);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Страница не найдена</title>
    <link rel="stylesheet" href="/errors/error.css">
</head>
<body>
<div class="error-wrap">
    <div class="error-card">
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Страница не найдена</h2>
        <p class="error-text">
            Похоже, такой страницы не существует, она была удалена или адрес введён неправильно.
        </p>

        <div class="error-actions">
            <a class="btn btn-primary" href="/">На главную</a>
            <a class="btn btn-secondary" href="javascript:history.back()">Назад</a>
        </div>

        <div class="error-note">Проверьте адрес или вернитесь на главную страницу.</div>
    </div>
</div>
</body>
</html>
