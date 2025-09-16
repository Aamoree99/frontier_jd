<?php
$host = "127.0.0.1";
$user = "root";
$pass = "пароль";
$db   = "zx";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die(json_encode(["status"=>"error","message"=>"DB connection failed"]));
}
mysqli_set_charset($conn, "utf8");

// возвращаем подключение
return $conn;
