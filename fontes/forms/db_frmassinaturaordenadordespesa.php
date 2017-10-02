<?php
if ($db_opcao == 1) {
  $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
}
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <form name="form1" id="form1" method="post" action="">
    <fieldset>
      <legend>Ordenador da Despesa</legend>
      <table>
        <tr style="display: none;">
          <td nowrap title="Sequencial" >
            <label class="bold" for="sequencial" id="lbl_sequencial">Sequencial:</label>
          </td>
          <td>
            <?php
            db_input('sequencial', 10, 1, true, 'text', 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Departamento" >
            <label class="bold" for="departamento" id="lbl_departamento">
              <?php
              db_ancora( 'Departamento:',
                "js_pesquisadepartamento(true);", $db_opcao);
              ?>
            </label>
          </td>
          <td>
            <?php
            $Sdepartamento = "Departamento";
            db_input('departamento', 10, 1, true, 'text', $db_opcao," onchange='js_pesquisadepartamento(false);'");
            ?>
            <?php
            db_input('descrdepto', 40, 0, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Ativo" >
            <label class="bold" for="ativo" id="lbl_ativo">Ativo:</label>
          </td>
          <td>
            <?php
            $aAtivo = array("t" => "Sim", "f" => "Não");
            db_select('ativo', $aAtivo, true, $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Principal" >
            <label class="bold" for="principal" id="lbl_principal">Principal:</label>
          </td>
          <td>
            <?php
            $aPrincipal = array("t" => "Sim", "f" => "Não");
            db_select('principal', $aPrincipal, true, $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Numero do CGM">
          <?
             db_ancora("CGM","js_pesquisa_numcgm(true);",$db_opcao);
           ?>
          </td>
          <td nowrap="nowrap">
            <?
              db_input('numcgm',10,1,true,'text',$db_opcao," onchange='js_pesquisa_numcgm(false);'");
              db_input('z01_nome',40,0,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Título" >
            <label class="bold" for="titulo" id="lbl_titulo">Título:</label>
          </td>
          <td>
            <?php db_input('titulo', 50, 0, true, 'text', $db_opcao, ""); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
    <?php if ($db_opcao != 1) : ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php endif ?>
  </form>
</div>
<?php db_menu( db_getsession("DB_id_usuario"),
  db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),
  db_getsession("DB_instit") ); ?>

<script>
  $('principal').style.width = '100px';
  $('ativo').style.width     = '100px';

  <?php
  // Se não é tela de exclusão
  if(!in_array($db_opcao, array(3, 33))) :
  ?>
  document.observe('dom:loaded', function(){

    $('form1').observe('submit', function(oEvent){

      if (empty($F('departamento'))) {

        alert('O campo Departamento é de preenchimento obrigatório.');
        return oEvent.preventDefault();
      }

      if (empty($F('nome'))) {

        alert('O campo Nome é de preenchimento obrigatório.');
        return oEvent.preventDefault();
      }

      if (empty($F('titulo'))) {

        alert('O campo Título é de preenchimento obrigatório.');
        return oEvent.preventDefault();
      }

    });

  });
  <?php endif ?>

  function js_pesquisadepartamento(lExibeJanela) {

    if (lExibeJanela) {

      js_OpenJanelaIframe( '(window.CurrentWindow || parent.CurrentWindow).corpo',
        'db_iframe_db_depart',
        'func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto',
        'Pesquisa', true);
    } else {

      if (document.form1.departamento.value != '') {

        $('db_opcao').disabled = true;
        js_OpenJanelaIframe( '(window.CurrentWindow || parent.CurrentWindow).corpo',
          'db_iframe_db_depart',
          'func_db_depart.php?pesquisa_chave=' + document.form1.departamento.value + '&funcao_js=parent.js_mostradb_depart',
          'Pesquisa', false);
      } else {

        document.form1.descrdepto.value = '';
        $('db_opcao').disabled          = false;
      }
    }
  }

  function js_mostradb_depart(sChave, lErro) {

    document.form1.descrdepto.value = sChave;
    $('db_opcao').disabled          = false;

    if (lErro) {

      document.form1.departamento.focus();
      document.form1.departamento.value = '';
    }
  }

  function js_mostradb_depart1(sChave, sDescricao) {

    $('db_opcao').disabled            = false;
    document.form1.departamento.value = sChave;
    document.form1.descrdepto.value   = sDescricao;
    db_iframe_db_depart.hide();
  }

  function js_pesquisa_numcgm(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0','1');
    }else{
       if(document.form1.numcgm.value != ''){
          js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
       }else{
         document.form1.z01_nome.value = '';
       }
    }
  }

  function js_mostracgm(erro,chave){
  
    document.form1.z01_nome.value = chave;
    if(erro==true){
      document.form1.numcgm.focus();
      document.form1.numcgm.value = '';
    }
    
  }

  function js_mostracgm1(chave1,chave2){
  
    document.form1.numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_cgm.hide();
  
  }
  

  function js_pesquisa() {

    js_OpenJanelaIframe( '(window.CurrentWindow || parent.CurrentWindow).corpo',
      'db_iframe_assinaturaordenadordespesa',
      'func_assinaturaordenadordespesa.php?funcao_js=parent.js_preenchepesquisa|0|1|2|3|4|5|6|7',
      'Pesquisa', true);
  }

  function js_preenchepesquisa() {

    $('sequencial').value   = arguments[0];
    $('departamento').value = arguments[1];
    $('descrdepto').value   = arguments[2];
    $('numcgm').value       = arguments[3];
    $('z01_nome').value         = arguments[4];
    $('titulo').value       = arguments[5];
    $('principal').value    = arguments[6];
    $('ativo').value        = arguments[7];

    db_iframe_assinaturaordenadordespesa.hide();
    <?php

      if ($db_opcao == 3 || $db_opcao == 33) {;
        echo "$('principal_select_descr').value = (arguments[7] == 't' ? 'Sim' : 'Não');\n";
        echo "return;\n";
      }
      
      if ($db_opcao != 1 && !empty($alterar)) {
        echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + arguments[0]  ;\n";
      }

    ?>
  }

  <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
</script>
</body>
</html>
