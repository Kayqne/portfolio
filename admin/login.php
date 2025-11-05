<?php
require __DIR__ . "/../includes/config.php";
if(is_logged_in()){ header("Location: painel.php"); exit; }
$err = "";
if($_SERVER["REQUEST_METHOD"]==="POST"){
  $u = $_POST["user"] ?? "";
  $p = $_POST["pass"] ?? "";
  if(do_login($u,$p)){
    header("Location: painel.php"); exit;
  } else {
    $err = "Usuário ou senha inválidos.";
  }
}
?><!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Camaleão CMS</title>
<link rel="stylesheet" href="../assets/style.css">
</head><body class="login-bg">
  <div class="login-card">
    <img src="../assets/images/logo-camaleao.svg" class="logo-login" alt="Camaleão">
    <h1>Camaleão CMS</h1>
    <?php if($err): ?><div class="alert"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
    <form method="post" class="login-form">
      <label>Usuário
        <input name="user" required value="admin">
      </label>
      <label>Senha
        <input type="password" name="pass" required value="camaleao123">
      </label>
      <button class="btn">Entrar</button>
    </form>
    <div class="login-foot">© 2025 Camaleão por Gabrielly Alves</div>
  </div>
</body></html>
