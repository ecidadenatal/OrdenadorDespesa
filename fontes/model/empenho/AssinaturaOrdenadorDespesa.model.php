<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
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
      throw new ParameterException("Informe o código do documento");
    }
    if (empty($iDepartamento)) {
      throw new ParameterException("Informe o departamento da Emissão do documento");
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