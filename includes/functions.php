<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function h(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return (($_SESSION['user_role'] ?? '') === 'admin');
}

function cartCount(): int
{
    $cart = $_SESSION['cart'] ?? [];
    return array_sum(array_map('intval', $cart));
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}