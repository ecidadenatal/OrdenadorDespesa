<?php
require_once "libs/db_stdlib.php";
require_once "libs/db_conecta_plugin.php";
require_once "libs/db_sessoes.php";
require_once "dbforms/db_funcoes.php";

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$oDaoAssinaturaordenadordespesa = new cl_assinaturaordenadordespesa;
$db_botao    = true;
$db_opcao    = 3;
$sPosScripts = "";

if (isset($excluir)) {

  $oDepartamento = new DBDepartamento($departamento);
  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoAssinaturaordenadordespesa->excluir($sequencial);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoAssinaturaordenadordespesa->erro_msg . '");' . "\n";

  if ($oDaoAssinaturaordenadordespesa->erro_status != "0") {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["_SERVER"]["PHP_SELF"]) . "';\n";
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $oDaoAssinaturaordenadordespesa->sql_record( $oDaoAssinaturaordenadordespesa->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 3) {
  $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "departamento", true, 1, "departamento", true);';

include("forms/db_frmassinaturaordenadordespesa.php");
?>
