<?php
require __DIR__ . "/../includes/config.php";
require_login();
require __DIR__ . "/../includes/functions.php";

$id = $_GET["id"] ?? "";
$data = cms_read_data($DATA_FILE);
[$idx, $sec] = find_section($data, $id);
if($idx < 0){ http_response_code(404); echo "Seção não encontrada."; exit; }

// salvar cabeçalho
if($_SERVER["REQUEST_METHOD"]==="POST" && ($_POST["action"] ?? "")==="save_head"){
  $data["sections"][$idx]["title"] = $_POST["title"] ?? $sec["title"];
  $data["sections"][$idx]["description"] = $_POST["description"] ?? $sec["description"];
  $data["sections"][$idx]["layout"] = $_POST["layout"] ?? $sec["layout"];
  cms_write_data($DATA_FILE, $data);
  header("Location: secao.php?id=" . urlencode($id)); exit;
}

// publicar/despublicar
if(isset($_GET["toggle_pub"])){
  $data["sections"][$idx]["published"] = empty($data["sections"][$idx]["published"]);
  cms_write_data($DATA_FILE, $data);
  header("Location: secao.php?id=" . urlencode($id)); exit;
}

// adicionar item
if($_SERVER["REQUEST_METHOD"]==="POST" && ($_POST["action"] ?? "")==="add_item"){
  $type = $_POST["type"] ?? "image";
  $caption = $_POST["caption"] ?? "";
  $desc = $_POST["desc"] ?? "";
  $src = $_POST["url"] ?? "";

  // upload de arquivo
  if(isset($_FILES["file"]) && $_FILES["file"]["error"]===UPLOAD_ERR_OK){
    $safe = sanitize_filename($_FILES["file"]["name"]);
    $dest = ($type==="video" ? $UPLOAD_VID : $UPLOAD_IMG) . uniqid() . "_" . $safe;
    @move_uploaded_file($_FILES["file"]["tmp_name"], $dest);
    $src = $dest;
    $src = str_replace(realpath(__DIR__ . "/.."), $BASE_URL, realpath($src)); // converte para URL absoluta
    $src = str_replace("\\","/",$src);
  }

  $item = [
    "id" => "it_" . bin2hex(random_bytes(6)),
    "type" => $type,
    "src" => $src,
    "caption" => $caption,
    "desc" => $desc,
    "published" => false
  ];
  $data["sections"][$idx]["items"][] = $item;
  cms_write_data($DATA_FILE, $data);
  header("Location: secao.php?id=" . urlencode($id)); exit;
}

// publicar item
if(isset($_GET["pub_item"])){
  $iid = $_GET["pub_item"];
  foreach($data["sections"][$idx]["items"] as &$it){
    if($it["id"]===$iid){ $it["published"] = empty($it["published"]); break; }
  }
  cms_write_data($DATA_FILE, $data);
  header("Location: secao.php?id=" . urlencode($id)); exit;
}

// remover item
if(isset($_GET["del_item"])){
  $iid = $_GET["del_item"];
  $items = array_values(array_filter($data["sections"][$idx]["items"], fn($x)=>$x["id"]!==$iid));
  $data["sections"][$idx]["items"] = $items;
  cms_write_data($DATA_FILE, $data);
  header("Location: secao.php?id=" . urlencode($id)); exit;
}

// reordenar via AJAX
if($_SERVER["REQUEST_METHOD"]==="POST" && ($_POST["action"] ?? "")==="reorder"){
  $order = $_POST["order"] ?? "";
  $ids = array_filter(explode(",", $order));
  $cur = $data["sections"][$idx]["items"];
  $map = [];
  foreach($cur as $it){ $map[$it["id"]]=$it; }
  $new = [];
  foreach($ids as $k){ if(isset($map[$k])) $new[] = $map[$k]; }
  $data["sections"][$idx]["items"] = $new;
  cms_write_data($DATA_FILE, $data);
  die("OK");
}

$sec = $data["sections"][$idx];
?><!doctype html><html lang="pt-br"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Seção — <?php echo htmlspecialchars($sec["title"]); ?></title>
<link rel="stylesheet" href="../assets/style.css">
<script defer src="../assets/script.js"></script>
</head><body>
<header class="adminbar">
  <div class="container bar__inner">
    <div class="brand">
      <img src="../assets/images/logo-camaleao.svg" alt="Camaleão">
      <strong><?php echo htmlspecialchars($sec["title"]); ?></strong>
    </div>
    <nav>
      <a class="btn ghost" href="painel.php">voltar</a>
      <a class="btn ghost" target="_blank" href="../portfolio.php">Ver Portfolio</a>
      <a class="btn danger" href="logout.php">Sair</a>
    </nav>
  </div>
</header>

<main class="container admin">
  <section class="card">
    <form method="post" class="sec-head">
      <input type="hidden" name="action" value="save_head">
      <div class="row2">
        <label>Título<input name="title" value="<?php echo htmlspecialchars($sec["title"]); ?>"></label>
        <label>Descrição<input name="description" value="<?php echo htmlspecialchars($sec["description"]); ?>"></label>
      </div>
      <div class="layout-opts small">
        <?php $opts=["grid-3"=>"Grid 3x3","grid-4"=>"4 colunas","mosaic-hero"=>"Destaque","mosaic-columns"=>"Colunas","row-gallery"=>"Faixa"];
        foreach($opts as $val=>$lab): $ck = ($sec["layout"]===$val) ? "checked" : ""; ?>
          <label><input type="radio" name="layout" value="<?php echo $val; ?>" <?php echo $ck; ?>><span class="demo <?php echo $val; ?>"></span> <?php echo $lab; ?></label>
        <?php endforeach; ?>
      </div>
      <div class="row-end">
        <button class="btn">Salvar</button>
        <a class="btn" href="secao.php?id=<?php echo urlencode($sec["id"]); ?>&toggle_pub=1"><?php echo empty($sec["published"]) ? "Publicar seção" : "Despublicar seção"; ?></a>
      </div>
    </form>
  </section>

  <section class="card">
    <h3>Itens (arraste para reordenar)</h3>
    <div class="mosaic mosaic-preview <?php echo htmlspecialchars($sec["layout"]); ?>" id="mosaic">
      <?php foreach($sec["items"] as $it): ?>
        <div class="tile tile-draggable" draggable="true" data-id="<?php echo htmlspecialchars($it["id"]); ?>">
          <?php if(($it["type"] ?? "image")==="video"): ?>
            <video src="<?php echo htmlspecialchars($it["src"]); ?>" autoplay loop muted playsinline></video>
          <?php else: ?>
            <img src="<?php echo htmlspecialchars($it["src"]); ?>" alt="">
          <?php endif; ?>
          <span class="cap"><?php echo htmlspecialchars($it["caption"] ?? ""); ?></span>
          <div class="tile-actions">
            <a class="btn small" href="secao.php?id=<?php echo urlencode($sec["id"]); ?>&pub_item=<?php echo urlencode($it["id"]); ?>"><?php echo empty($it["published"]) ? "Publicar" : "Rascunho"; ?></a>
            <a class="btn small danger" onclick="return confirm('Remover item?')" href="secao.php?id=<?php echo urlencode($sec["id"]); ?>&del_item=<?php echo urlencode($it["id"]); ?>">Remover</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="card">
    <h3>Adicionar item</h3>
    <form class="add-item dropzone" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add_item">
      <div class="row3">
        <label>Tipo
          <select name="type" class="typeSel" onchange="togglePoster(this)">
            <option value="image">Imagem</option>
            <option value="video">Vídeo</option>
          </select>
        </label>
        <label>Arquivo (ou URL)
          <input type="file" name="file" accept="image/*,video/mp4">
          <input type="url" name="url" placeholder="ou cole uma URL https://">
        </label>
        <label>Legenda
          <input name="caption" placeholder="Ex.: Identidade X, 2025">
        </label>
      </div>
      <div class="row2">
        <label>Descrição
          <input name="desc" placeholder="Texto da página do projeto">
        </label>
      </div>
      <div class="dz-help muted">Dica: arraste arquivos aqui para anexar. Itens entram como <strong>rascunho</strong>.</div>
      <button class="btn">Adicionar</button>
    </form>
  </section>
</main>

<form id="reorderForm" method="post" style="display:none">
  <input type="hidden" name="action" value="reorder">
  <input type="hidden" name="order" id="orderInput">
</form>
</body></html>
