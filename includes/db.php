<?php

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'shop_db';

$conn = new mysqli(
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME
);

if ($conn->connect_error) {
    die('Ошибка подключения к БД');
}

$conn->set_charset('utf8mb4');