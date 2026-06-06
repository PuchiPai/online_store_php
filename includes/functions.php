<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn()
{
    return !empty($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['user_role'])
        && $_SESSION['user_role'] === 'admin';
}

function cartCount()
{
    $cart = $_SESSION['cart'] ?? [];

    return array_sum($cart);
}

function redirect($path)
{
    header('Location: ' . $path);
    exit;
}