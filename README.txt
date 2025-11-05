Camaleão CMS v4 — por Qodemy (Master Kayque)
============================================

Estrutura:
----------
camaleao_cms/
  index.php                ← redireciona para portfolio.php
  portfolio.php            ← site público do portfólio
  assets/
    style.css              ← tema dark+gold (refinado)
    script.js              ← animações, lightbox e drag&drop (admin)
    images/logo-camaleao.svg
    videos/sample.mp4      ← coloque seus vídeos aqui (substitua este arquivo)
  admin/
    login.php              ← login do painel (admin / camaleao123)
    painel.php             ← dashboard (criar seção, listar seções)
    secao.php              ← editar seção (itens, upload, ordenar)
    upload.php             ← handler (stub, usamos secao.php)
    logout.php
  includes/
    config.php             ← caminhos, BASE_URL, credenciais
    functions.php          ← CRUD da seção
    db.php                 ← (placeholder) usamos JSON
  data/
    portfolio.json         ← banco de dados (JSON)
  uploads/
    imagens/               ← uploads de imagens
    videos/                ← uploads de vídeos

Instalação:
-----------
1) Envie a pasta "camaleao_cms" para o servidor:
   /public_html/camaleao_cms/

2) Ajuste a URL base em includes/config.php se necessário:
   $BASE_URL = "https://qodemy.com.br/camaleao_cms";

3) Permissões (se o JSON não salvar):
   chmod 777 camaleao_cms/data
   chmod -R 777 camaleao_cms/uploads

4) Acesse o painel:
   https://qodemy.com.br/camaleao_cms/admin/login.php
   Usuário: admin
   Senha: camaleao123

Uso rápido:
-----------
• No painel, clique em "Criar seção" e escolha um layout.
• Entre na seção criada e faça upload de imagens/vídeos.
• Clique em "Publicar seção" e depois em cada item "Publicar".
• O site público fica em /portfolio.php (botão "Ver Portfolio").

Observações:
------------
• O upload é processado em admin/secao.php (drag-and-drop no card da seção).
• Caso o vídeo não carregue, substitua assets/videos/sample.mp4 por um arquivo real.
• Este CMS usa JSON (sem MySQL) para instalação rápida.

Suporte:
--------
Qodemy • camaleão por Gabrielly Alves
