<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oParam              = json_decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->erro      = false;
$oRetorno->mensagem  = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getOrdenadores":

      $_SESSION["EMPENHO_ORDENADOR_DESPESA"] = '';

      $oDaoOrdenador =  new cl_assinaturaordenadordespesa();
      $iDepartamento = db_getsession("DB_coddepto");

      $sCampos = "sequencial as codigo, z01_nome as nome, principal";
      $sOrder  = "principal desc;";
      $sWhere  = "ativo is true and departamento = {$iDepartamento}";

      $sBuscaOrdenador = $oDaoOrdenador->sql_query(null, $sCampos, $sOrder, $sWhere);
      $rsOrdenadores   = $oDaoOrdenador->sql_record($sBuscaOrdenador);

      $oRetorno->ordernador_documento = '';
      if (!empty($oParam->iTipo) && !empty($oParam->iDocumento)) {

        $oDaoOrdenadorAssinaturaDocumento = new cl_documentoassinaturaordenadordespesa();

        $sWhere         = "tipo = {$oParam->iTipo} and chave = '{$oParam->iDocumento}'";
        $sSqlAssinatura = $oDaoOrdenadorAssinaturaDocumento->sql_query_file(null, "assinaturaordenadordespesa", null, $sWhere);
        $rsAssinatura   = $oDaoOrdenadorAssinaturaDocumento->sql_record($sSqlAssinatura);
        if ($oDaoOrdenadorAssinaturaDocumento->numrows > 0) {
          $oRetorno->ordernador_documento = db_utils::fieldsMemory($rsAssinatura, 0)->assinaturaordenadordespesa;
        }
      }
      $oStdDados            = new stdClass();
      $oStdDados->codigo    = 0;
      $oStdDados->nome      = "Selecione";
      $oStdDados->principal = 'f';
      $oRetorno->ordenadores = array($oStdDados);
      if ($oDaoOrdenador->numrows > 0) {

        $oRetorno->ordenadores = db_utils::getCollectionByRecord($rsOrdenadores, false, false, true);
        db_putsession('EMPENHO_ORDENADOR_DESPESA', $oRetorno->ordenadores[0]->codigo);
        if (!empty($oRetorno->ordernador_documento)) {
          db_putsession('EMPENHO_ORDENADOR_DESPESA', $oRetorno->ordernador_documento);
        }
      }

      break;

    case "salvarOrdenadorNaSessao":

      db_putsession('EMPENHO_ORDENADOR_DESPESA', $oParam->codigo);
      $oRetorno->sessao = db_getsession('EMPENHO_ORDENADOR_DESPESA');
      break;
  }


} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->mensagem = $eErro->getMessage();
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo json_encode($oRetorno);