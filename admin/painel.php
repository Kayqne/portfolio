<?php
require __DIR__ . "/../includes/config.php";
require_login();
require __DIR__ . "/../includes/functions.php";

$msg = "";
if($_SERVER["REQUEST_METHOD"]==="POST" && ($_POST["action"] ?? "")==="create_section"){
  [$ok, $ret] = create_section($_POST["title"] ?? "", $_POST["description"] ?? "", $_POST["layout"] ?? "grid-3");
  if($ok){
    header("Location: secao.php?id=" . urlencode($ret)); exit;
  } else {
    $msg = $ret;
  }
}

$data = cms_read_data($DATA_FILE);
?><!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Painel — Camaleão CMS</title>
<link rel="stylesheet" href="../assets/style.css">
</head><body>
<header class="adminbar">
  <div class="container bar__inner">
    <div class="brand">
      <img src="../assets/images/logo-camaleao.svg" alt="Camaleão">
      <strong>Camaleão CMS</strong>
    </div>
    <nav>
      <a class="btn ghost" href="../portfolio.php" target="_blank">Ver Portfolio</a>
      <a class="btn danger" href="logout.php">Sair</a>
    </nav>
  </div>
</header>

<main class="container admin">
  <section class="card">
    <h2>Criar nova seção</h2>
    <?php if($msg): ?><div class="alert"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
    <form method="post" class="form-grid">
      <input type="hidden" name="action" value="create_section">
      <label>Título <input name="title" placeholder="Ex.: Identidades Visuais"></label>
      <label>Descrição <input name="description" placeholder="Resumo opcional da seção"></label>
      <fieldset class="layout-opts">
        <legend>Layout</legend>
        <label><input type="radio" name="layout" value="grid-3" checked> <span class="demo grid-3"></span> Grid 3x3</label>
        <label><input type="radio" name="layout" value="grid-4"> <span class="demo grid-4"></span> Grid 4 colunas</label>
        <label><input type="radio" name="layout" value="mosaic-hero"> <span class="demo mosaic-hero"></span> Destaque (hero)</label>
        <label><input type="radio" name="layout" value="mosaic-columns"> <span class="demo mosaic-columns"></span> Colunas</label>
        <label><input type="radio" name="layout" value="row-gallery"> <span class="demo row-gallery"></span> Faixa</label>
      </fieldset>
      <button class="btn">Criar seção</button>
    </form>
  </section>

  <section class="card">
    <h2>Seções existentes</h2>
    <div class="sections-list">
      <?php foreach($data["sections"] as $s): ?>
        <div class="section-item">
          <div>
            <div class="s-title"><?php echo htmlspecialchars($s["title"]); ?></div>
            <div class="s-desc"><?php echo htmlspecialchars($s["description"]); ?></div>
            <div class="s-meta"><?php echo htmlspecialchars($s["id"]); ?> • <?php echo $s["published"] ? "Publicado" : "Rascunho"; ?></div>
          </div>
          <div class="s-actions">
            <a class="btn" href="secao.php?id=<?php echo urlencode($s["id"]); ?>">Editar</a>
          </div>
        </div>
      <?php endforeach; if(empty($data["sections"])): ?>
        <div class="muted">Nenhuma seção criada ainda.</div>
      <?php endif; ?>
    </div>
  </section>
</main>
</body></html>
