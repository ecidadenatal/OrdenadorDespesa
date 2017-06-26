<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */


/**
 * Assinatura dos Ordenadores de Despesa
 * Class AssinaturaOrdenadorDespesa
 */
class AssinaturaOrdenadorDespesa {

  CONST ASSINATURA_EMISSAO_EMPENHO = 1;

  CONST ASSINATURA_ORDEM_PAGAMENTO = 2;

  CONST ASSINATURA_ANULACAO_EMPENHO = 3;

  /**
   * Retorna os Dados do Ordenador da despesa
   *
   * @param $iTipo
   * @param $iCodigoDocumento
   * @return mixed
   */
  static public function getAssinatura($iTipo, $iCodigoDocumento, $sTexto) {

    $sCampos         = "z01_nome, titulo ";
    $sWhere          = " tipo = {$iTipo} ";
    $sWhere         .= "and chave = {$iCodigoDocumento}";

    $sTitulo        = '';
    $sNomeOrdenador = 'Ordenador da Despesa';
    $oDaoAssinatura = new cl_assinaturaordenadordespesa;
    $sSqlAssinatura = $oDaoAssinatura->sql_query_assinatura($sCampos, $sWhere);

    $rsDadosAssinatura = db_query($sSqlAssinatura);
    if (pg_num_rows($rsDadosAssinatura) > 0) {

      $oDadosAssinatura = db_utils::fieldsMemory($rsDadosAssinatura, 0);
      $sTitulo          = $oDadosAssinatura->titulo;
      $sNomeOrdenador   = $oDadosAssinatura->z01_nome;
    }

    $sTexto = str_replace(array('[ordenador_titulo]', '[ordenador_nome]'),
      array($sTitulo, $sNomeOrdenador),
      $sTexto
    );
    return $sTexto;
  }

  /**
   * Persiste os dados do Ordenador da despesa
   *
   * @param $iTipo
   * @param $iCodigoDocumento
   * @param $iDepartamento
   * @throws BusinessException
   * @throws ParameterException
   */
  static public function gravarAssinatura($iTipo, $iCodigoDocumento, $iDepartamento) {

    if (empty($iTipo)) {
      throw new ParameterException("Informe o tipo do documento");
    }
    if (empty($iCodigoDocumento)) {
      throw new ParameterException("Informe o c�digo do documento");
    }
    if (empty($iDepartamento)) {
      throw new ParameterException("Informe o departamento da Emiss�o do documento");
    }

    if (empty($_SESSION["EMPENHO_ORDENADOR_DESPESA"])) {
      return;
    }

    $oDaoAssinatura = new cl_documentoassinaturaordenadordespesa();

    /**
     * Verificamos se existe assinatura para o tipo e o documento.
     * Caso exista, excluimos o mesmo e incluimos novamente
     */
    $sWhereAssinatura = "tipo = {$iTipo} and chave = '{$iCodigoDocumento}'";
    $oDaoAssinatura->excluir(null, $sWhereAssinatura);
    if ($oDaoAssinatura->erro_status == 0) {
      throw new BusinessException("Erro ao o incluir os dados do ordenador para o documento {$iCodigoDocumento}");
    }
    $oDaoAssinatura->assinaturaordenadordespesa = db_getsession("EMPENHO_ORDENADOR_DESPESA");
    $oDaoAssinatura->tipo                       = $iTipo;
    $oDaoAssinatura->chave                      = $iCodigoDocumento;
    $oDaoAssinatura->incluir(null);
    if ($oDaoAssinatura->erro_status == 0) {
      throw new BusinessException("Erro ao o incluir os dados do ordenador para o documento {$iCodigoDocumento}");
    }
  }
}