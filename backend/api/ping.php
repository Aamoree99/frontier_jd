<?php
require_once "../db.php";

echo json_encode([
    "status" => "ok",
    "message" => "DB connected"
]);
