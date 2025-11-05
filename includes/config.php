<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// === CONFIGURAÇÕES BASE ===
$BASE_URL   = "https://qodemy.com.br/camaleao"; // altere se necessário
$BASE_DIR   = __DIR__ . "/..";
$DATA_FILE  = $BASE_DIR . "/data/portfolio.json";
$UPLOAD_DIR = $BASE_DIR . "/uploads/";
$UPLOAD_IMG = $UPLOAD_DIR . "imagens/";
$UPLOAD_VID = $UPLOAD_DIR . "videos/";

// === LOGIN (admin / camaleao123) ===
$ADMIN_USER = "admin";
$ADMIN_PASS_HASH = password_hash("camaleao123", PASSWORD_DEFAULT);

// === FUNÇÕES BÁSICAS ===
function is_logged_in(){ return !empty($_SESSION['cms_logged']); }
function require_login(){ if(!is_logged_in()){ header("Location: login.php"); exit; } }
function do_login($u,$p){
  global $ADMIN_USER, $ADMIN_PASS_HASH;
  if ($u === $ADMIN_USER && password_verify($p, $ADMIN_PASS_HASH)) {
    $_SESSION['cms_logged'] = true;
    return true;
  }
  return false;
}
function do_logout(){ $_SESSION = []; session_destroy(); header("Location: login.php"); exit; }

function cms_read_data($file){
  if (!file_exists($file)) return ["sections"=>[]];
  $raw = file_get_contents($file);
  $data = json_decode($raw, true);
  if(!$data || !isset($data['sections'])) $data = ["sections"=>[]];
  return $data;
}
function cms_write_data($file,$data){
  $dir = dirname($file);
  if(!is_dir($dir)) { @mkdir($dir, 0777, true); }
  if(!is_writable($dir)) { @chmod($dir, 0777); }
  if(!file_exists($file)) { @touch($file); @chmod($file, 0666); }
  $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
  $ok = @file_put_contents($file, $json);
  if($ok === false){
    @file_put_contents($dir."/write_error.log", date("c")." Falha ao gravar JSON\n", FILE_APPEND);
    return false;
  }
  return true;
}
function sanitize_filename($name){ return preg_replace('/[^a-zA-Z0-9-_\.]/','_', basename($name)); }

// Garante pastas de upload
@mkdir($UPLOAD_DIR, 0777, true);
@mkdir($UPLOAD_IMG, 0777, true);
@mkdir($UPLOAD_VID, 0777, true);
@chmod($UPLOAD_DIR, 0777);
@chmod($UPLOAD_IMG, 0777);
@chmod($UPLOAD_VID, 0777);
?>
