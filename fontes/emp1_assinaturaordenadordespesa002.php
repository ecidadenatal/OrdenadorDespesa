<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta_plugin.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$oDaoAssinaturaordenadordespesa = new cl_assinaturaordenadordespesa;
$db_opcao    = 2;
$db_botao    = true;
$sPosScripts = "";

if (isset($alterar)) {

  $lErroAtualizacao = false;
  db_inicio_transacao();
  $oDepartamento = new DBDepartamento($departamento);
  if ($principal == 't') {

    $sSqlUpdate  = "update assinaturaordenadordespesa ";
    $sSqlUpdate .= "   set principal = 'false' ";
    $sSqlUpdate .= " where departamento = {$departamento} ";
    $rsAtualizaAssinatura = db_query($sSqlUpdate);

    if (!$rsAtualizaAssinatura) {

      $lErroAtualizacao = true;
      $oDaoAssinaturaordenadordespesa->erro_msg = "Não foi possível atualizar as assinaturas com o departamento {$departamento} - {$descrdepto}.";
    }
  }

  if ( ! $lErroAtualizacao) {

    $db_opcao = 2;
    $oDaoAssinaturaordenadordespesa->alterar($sequencial);
  }

  db_fim_transacao();
  $sPosScripts .= 'alert("' . $oDaoAssinaturaordenadordespesa->erro_msg . '");' . "\n";

  if ($oDaoAssinaturaordenadordespesa->erro_status == "0") {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoAssinaturaordenadordespesa->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoAssinaturaordenadordespesa->erro_campo}.classList.add('form-error');";
      $sPosScripts .= "document.form1.{$oDaoAssinaturaordenadordespesa->erro_campo}.focus();";
    }
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $db_botao = true;
  $result   = $oDaoAssinaturaordenadordespesa->sql_record( $oDaoAssinaturaordenadordespesa->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 2 && empty($chavepesquisa)) {
  $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "departamento", true, 1, "departamento", true);';

include(modification("forms/db_frmassinaturaordenadordespesa.php"));
?>
