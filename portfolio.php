<?php
require __DIR__ . "/includes/config.php";
$data = cms_read_data($DATA_FILE);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Camaleão - Gabrielly Alves</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="site">
 <header class="sitebar">
  <div class="container bar__inner">
    <div class="brand">
      <img src="assets/images/logo-camaleao.svg" alt="">
      <strong>Camaleão</strong>
    </div>

    <nav class="nav-links">
      <a href="#sobre">Sobre</a>
      <a href="#portfolio">Portfólio</a>
      <a href="admin/login.php" class="login-link">Entrar</a>
    </nav>
  </div>
</header>


  <!-- INTRO -->
<section id="intro" class="intro-tight">
  <div class="intro-left">
    <div class="pill">
      <span class="dot"></span>
      <strong>CAMALEÃO</strong>
    </div>
    <h1>OLÁ<br>BEM<br>VINDOS</h1>
  </div>

  <div class="intro-mosaic">
    <video src="assets/videos/01.mp4" autoplay muted loop playsinline></video>
    <video src="assets/videos/02.mp4" autoplay muted loop playsinline></video>
    <video src="assets/videos/03.mp4" autoplay muted loop playsinline></video>
    <video src="assets/videos/04.mp4" autoplay muted loop playsinline></video>
  </div>
</section>


<!-- SOBRE -->
<section id="sobre" class="sobre-split">
  <div class="sobre-label" aria-hidden="true">SOBRE</div>

  <div class="sobre-photo">
    <img src="assets/images/gabrielly.jpg" alt="Gabrielly Alves">
    <div class="fade-left"></div>
    <div class="fade-bottom"></div>
  </div>

  <div class="sobre-copy">
    <h2>EU SOU A<br>GABRIELLY ALVES</h2>
    <p>
      Sou apaixonada por criar e comunicar através do design. 
      Acredito que cada projeto é uma oportunidade de transformar ideias em experiências visuais que conectam pessoas e marcas. 
      Meu trabalho une criatividade, estratégia e propósito, buscando sempre transmitir mensagens de forma autêntica, estética e eficiente.
    </p>
  </div>
</section>


  <!-- PORTFOLIO -->
  <main class="container portfolio" id="portfolio">
    <?php foreach($data["sections"] as $s): if(empty($s["published"])) continue; ?>
      <section class="site-section">
        <div class="site-head">
          <h2><?php echo htmlspecialchars($s["title"]); ?></h2>
          <p><?php echo htmlspecialchars($s["description"]); ?></p>
        </div>
        <div class="mosaic <?php echo htmlspecialchars($s["layout"]); ?>">
          <?php foreach($s["items"] as $it): if(empty($it["published"])) continue; ?>
            <a class="tile lightbox" href="<?php echo htmlspecialchars($it["src"]); ?>" data-type="<?php echo htmlspecialchars($it["type"]); ?>" title="<?php echo htmlspecialchars($it["caption"]); ?>">
              <?php if(($it["type"] ?? "image")==="video"): ?>
                <video src="<?php echo htmlspecialchars($it["src"]); ?>" autoplay loop muted playsinline></video>
              <?php else: ?>
                <img src="<?php echo htmlspecialchars($it["src"]); ?>" alt="">
              <?php endif; ?>
              <?php if(!empty($it["caption"])): ?><span class="cap"><?php echo htmlspecialchars($it["caption"]); ?></span><?php endif; ?>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; if(empty(array_filter($data["sections"], fn($x)=>!empty($x["published"])))): ?>
      <div class="muted" style="padding:40px 0;text-align:center;">Nenhuma seção publicada ainda.</div>
    <?php endif; ?>
  </main>

  <footer class="footer">© 2025 Camaleão por Gabrielly Alves — desenvolvido por Qodemy</footer>
</body>
</html>
