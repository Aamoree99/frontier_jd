<?php
header('Content-Type: application/json');
/** @var mysqli $conn */
$conn = require '../db.php';


// Получаем POST данные
$name       = trim($_POST['name'] ?? '');
$passwd     = trim($_POST['passwd'] ?? '');
$prompt     = trim($_POST['Prompt'] ?? '');
$answer     = trim($_POST['answer'] ?? '');
$email      = trim($_POST['email'] ?? '');
$referal    = intval($_POST['referal'] ?? 0);
$register_gold = intval($_POST['register_gold'] ?? 0);

// Проверка обязательных полей
if (!$name || !$passwd || !$prompt || !$answer || !$email) {
    echo json_encode(["status"=>"error", "message"=>"Пожалуйста, заполните все обязательные поля"]);
    exit;
}

// Проверка уникальности имени
$stmt = $conn->prepare("SELECT ID FROM users WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["status"=>"error", "message"=>"Имя уже занято"]);
    exit;
}
$stmt->close();

// Проверка уникальности email
$stmt = $conn->prepare("SELECT ID FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["status"=>"error", "message"=>"Email уже используется"]);
    exit;
}
$stmt->close();

// Генерация нового ID (по старой логике: max(id)+16)
$result = $conn->query("SELECT IFNULL(MAX(ID),16)+16 AS newid FROM users");
$row = $result->fetch_assoc();
$userid = intval($row['newid']);

// MD5 пароля
$hash_pass = md5($passwd);

// Вставка пользователя
$stmt = $conn->prepare("
    INSERT INTO users 
    (ID, name, passwd, Prompt, answer, email, creatime, referal) 
    VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
");
$stmt->bind_param("isssssi", $userid, $name, $hash_pass, $prompt, $answer, $email, $referal);

if ($stmt->execute()) {
    // Если есть бонус за регистрацию
    if ($register_gold > 0 && function_exists('UseCash')) {
        UseCash($userid, 0, 0, $register_gold*100); // zoneid и aid можно заменить при необходимости
    }
    echo json_encode(["status"=>"ok", "message"=>"Регистрация успешна", "userid"=>$userid]);
} else {
    echo json_encode(["status"=>"error", "message"=>"Ошибка базы данных: ".$stmt->error]);
}

$stmt->close();
$conn->close();
