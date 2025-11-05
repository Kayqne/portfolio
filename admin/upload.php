<?php
require __DIR__ . "/../includes/config.php";
require_login();
header("Content-Type: application/json");
echo json_encode(["ok"=>true,"msg"=>"Use o formulário em secao.php para uploads."]);
