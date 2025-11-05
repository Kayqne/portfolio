<?php
require_once __DIR__ . "/config.php";

function create_section($title, $desc, $layout){
  global $DATA_FILE;
  $data = cms_read_data($DATA_FILE);
  $id = "sec_" . bin2hex(random_bytes(6));
  $section = [
    "id" => $id,
    "title" => $title ?: "Nova seção",
    "description" => $desc ?: "",
    "layout" => $layout ?: "grid-3",
    "published" => false,
    "items" => []
  ];
  $data["sections"][] = $section;
  if(!cms_write_data($DATA_FILE,$data)) return [false, "Permissão negada ao salvar JSON."];
  return [true, $id];
}

function find_section(&$data, $id){
  foreach($data["sections"] as $k=>$s){
    if($s["id"] === $id) return [$k, $s];
  }
  return [-1, null];
}
?>
