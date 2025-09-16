<?php

require_once "../db.php";
header('Content-Type: application/json');

/** @var mysqli $conn */

$name = trim($_POST['name'] ?? '');
$pass = trim($_POST['passwd'] ?? '');

if (!$name || !$pass) {
    echo json_encode(["status" => "error", "message" => "Введите имя и пароль"]);
    exit;
}

// хешируем пароль
$pass_md5 = md5($pass);

// проверяем в базе
$sql = "SELECT ID, name, email, Prompt, truename FROM users 
        WHERE name='" . mysqli_real_escape_string($conn, $name) . "' 
          AND passwd='$pass_md5'";

$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode(["status" => "error", "message" => "Неверный логин или пароль"]);
    exit;
}

// получаем данные пользователя
$user = mysqli_fetch_assoc($res);

echo json_encode([
    "status" => "ok",
    "message" => "Вход успешен",
    "user" => $user
]);
