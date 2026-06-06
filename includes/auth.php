<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: /pages/login.php');
        exit;
    }
}

function requireAdmin(): void
{
    if (empty($_SESSION['user_id']) || (($_SESSION['user_role'] ?? '') !== 'admin')) {
        die('Доступ запрещен');
    }
}

function currentUserId(): int
{
    return (int)($_SESSION['user_id'] ?? 0);
}

function currentUserName(): string
{
    return (string)($_SESSION['user_name'] ?? '');
}

function currentUserRole(): string
{
    return (string)($_SESSION['user_role'] ?? '');
}