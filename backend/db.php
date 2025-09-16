<?php
$host = "127.0.0.1";
$user = "phpuser";
$pass = "php_pass";
$db   = "zx";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die(json_encode([
        "status"=>"error",
        "message"=>mysqli_connect_error()
    ]));
}
mysqli_set_charset($conn, "utf8");

// возвращаем подключение
return $conn;
