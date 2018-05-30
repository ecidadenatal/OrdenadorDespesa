require_once('scripts/AjaxRequest.js');

const ORDENADOR_ASSINATURA_EMISSAO_EMPENHO  = 1;
const ORDENADOR_ASSINATURA_ORDEM_PAGAMENTO  = 2;
const ORDENADOR_ASSINATURA_ANULACAO_EMPENHO = 3;
const ORDENADOR_ASSINATURA_ANULACAO_ORDEM_PAGAMENTO = 4;
const ORDENADOR_ASSINATURA_REMESSA_BANCARIA = 5;

OrdenadorDespesa = function() {


  /**
   * @type {Array}
   */
  this.aOrdenadores = [];

  this.iDocumento;

  this.iTipo;

  this.iCodigoOrdenador = '';
  /**
   * Adiciona o combobox na tela
   * @param oContainer
   */
  this.show = function (oContainer) {

    this.carregarInformacoes();

    var self      = this;
    var oComboBox = document.createElement("select");
    oComboBox.id  = "ordenador_da_despesa";
    oComboBox.name  = "ordenador_da_despesa";
    this.aOrdenadores.each(function(oOrdenador) {

      var oOption = document.createElement('option');
      oOption.value     = oOrdenador.codigo;
      oOption.innerHTML = oOrdenador.nome.urlDecode();
      if (oOrdenador.codigo == self.iCodigoOrdenador) {
        oOption.selected = true;
      }

      if (oOrdenador.principal == 't') {
        oOption.innerHTML += " - Principal";
      }
      oComboBox.appendChild(oOption);
    });

    oContainer.appendChild(oComboBox);
    oComboBox.observe('change', this.salvarOrdenador);
  };

  /**
   * Salva o ordenador na sess�o
   */
  this.salvarOrdenador = function() {

    new AjaxRequest(
      'emp4_ordenadordespesa.RPC.php',
      {exec: 'salvarOrdenadorNaSessao', codigo: $F('ordenador_da_despesa')},
      function (oRetorno, lErro) {

        if (lErro) {
          return alert(oRetorno.mensagem.urlDecode());
        }
      }
    ).asynchronous(false).setMessage('Aguarde, salvando ordenador da despesa...').execute();
  };

  /**
   * Seta o tipo do documento
   * @param integer iTipo Tipo documento
   */
  this.setTipo = function(iTipo) {
    this.iTipo = iTipo;
  }

  /**
   * Seta o numero do documento que foi assinado
   * @param iDocumento
   */
  this.setDocumento = function(iDocumento) {
    this.iDocumento = iDocumento;
  }


  /**
   * Busca os ordenadores cadastrados
   */
  this.carregarInformacoes = function() {

    var self = this;
    new AjaxRequest(
      'emp4_ordenadordespesa.RPC.php',
      {exec:'getOrdenadores', iTipo : self.iTipo, iDocumento: self.iDocumento},
      function (oRetorno, lErro) {

        if (lErro) {
          return alert(oRetorno.mensagem.urlDecode());
        }
        self.iCodigoOrdenador = oRetorno.ordernador_documento;
        self.aOrdenadores = oRetorno.ordenadores;
      }
    ).asynchronous(false).execute();
  };
};
