<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin()
{
    if (empty($_SESSION['user_id'])) {
        header('Location: /pages/login.php');
        exit;
    }
}

function requireAdmin()
{
    if (
        empty($_SESSION['user_id']) ||
        ($_SESSION['user_role'] ?? '') !== 'admin'
    ) {
        die('Доступ запрещен');
    }
}

function currentUserId()
{
    return (int)($_SESSION['user_id'] ?? 0);
}

function currentUserName()
{
    return (string)($_SESSION['user_name'] ?? '');
}

function currentUserRole()
{
    return (string)($_SESSION['user_role'] ?? '');
}