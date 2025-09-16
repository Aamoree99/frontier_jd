<?php
require_once "../db.php";
header('Content-Type: application/json');

/** @var mysqli $conn */

// обязательные поля
$name   = trim($_POST['name'] ?? '');
$pass   = trim($_POST['passwd'] ?? '');
$email  = trim($_POST['email'] ?? '');
$prompt = trim($_POST['Prompt'] ?? '');

// необязательные
$answer       = trim($_POST['answer'] ?? '');
$truename     = trim($_POST['truename'] ?? '');
$mobilenumber = trim($_POST['mobilenumber'] ?? '');
$province     = trim($_POST['province'] ?? '');
$city         = trim($_POST['city'] ?? '');
$phonenumber  = trim($_POST['phonenumber'] ?? '');
$address      = trim($_POST['address'] ?? '');
$postalcode   = trim($_POST['postalcode'] ?? '');
$gender       = intval($_POST['gender'] ?? 0);
$birthday     = $_POST['birthday'] ?? NULL;
$qq           = trim($_POST['qq'] ?? '');
$passwd2      = trim($_POST['passwd2'] ?? '');

// проверка обязательных
if (!$name || !$pass || !$email || !$prompt) {
    echo json_encode(["status"=>"error","message"=>"Заполните все обязательные поля"]);
    exit;
}

// проверка уникальности имени
$res = mysqli_query($conn, "SELECT ID FROM users WHERE name='".mysqli_real_escape_string($conn, $name)."'");
if (mysqli_num_rows($res) > 0) {
    echo json_encode(["status"=>"error","message"=>"Имя уже занято"]);
    exit;
}

// хеш пароля MD5 для совместимости с игрой
$pass_md5  = md5($pass);
$pass2_md5 = $passwd2 ? md5($passwd2) : NULL;

// формат даты рождения
$birthday_sql = $birthday ? "'".mysqli_real_escape_string($conn, $birthday)."'" : "NULL";

// вставка пользователя
$sql = "INSERT INTO users 
(name, passwd, Prompt, answer, truename, email, mobilenumber, province, city, phonenumber, address, postalcode, gender, birthday, creatime, qq, passwd2)
VALUES (
'".mysqli_real_escape_string($conn,$name)."',
'$pass_md5',
'".mysqli_real_escape_string($conn,$prompt)."',
'".mysqli_real_escape_string($conn,$answer)."',
'".mysqli_real_escape_string($conn,$truename)."',
'".mysqli_real_escape_string($conn,$email)."',
'".mysqli_real_escape_string($conn,$mobilenumber)."',
'".mysqli_real_escape_string($conn,$province)."',
'".mysqli_real_escape_string($conn,$city)."',
'".mysqli_real_escape_string($conn,$phonenumber)."',
'".mysqli_real_escape_string($conn,$address)."',
'".mysqli_real_escape_string($conn,$postalcode)."',
$gender,
$birthday_sql,
NOW(),
'".mysqli_real_escape_string($conn,$qq)."',
".($pass2_md5 ? "'$pass2_md5'" : "NULL")."
)";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["status"=>"ok","message"=>"Регистрация успешна"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Ошибка базы: ".mysqli_error($conn)]);
}
