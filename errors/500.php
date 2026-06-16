<?php
http_response_code(500);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Ошибка сервера</title>
    <link rel="stylesheet" href="/errors/error.css">
</head>
<body>
<div class="error-wrap">
    <div class="error-card">
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Внутренняя ошибка сервера</h2>
        <p class="error-text">
            Что-то пошло не так на сервере. Мы уже увидели проблему и пытаемся её исправить.
        </p>

        <div class="error-actions">
            <a class="btn btn-primary" href="/">На главную</a>
            <a class="btn btn-secondary" href="">Обновить страницу</a>
        </div>

        <div class="error-note">Если ошибка повторяется, попробуйте зайти позже.</div>
    </div>
</div>
</body>
</html>
