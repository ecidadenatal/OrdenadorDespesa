<?
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta_plugin.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$classinaturaordenadordespesa = new cl_assinaturaordenadordespesa;
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label>Código:</label></td>
          <td><? db_input("sequencial",10,3,true,"text",4,"","chave_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label>Nome:</label></td>
          <td><? db_input("nome", 40,0,true,"text",4,"","chave_nome");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_assinaturaordenadordespesa.hide();">
  </form>
      <?


      $campos = 'sequencial as dl_Código, departamento as dl_Departamento, descrdepto as dl_Descrição_do_Departamento, z01_numcgm, z01_nome, titulo as dl_Título, principal as dl_Principal, ativo as dl_Ativo';
      if(!isset($pesquisa_chave)){

        if(isset($chave_sequencial) && (trim($chave_sequencial)!="") ){
	         $sql = $classinaturaordenadordespesa->sql_query($chave_sequencial,$campos,"sequencial");
        }else if(isset($chave_nome) && (trim($chave_nome)!="") ){
	         $sql = $classinaturaordenadordespesa->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_nome%' ");
        }else{
           $sql = $classinaturaordenadordespesa->sql_query("",$campos,"sequencial","");
        }
        $repassa = array();
        if(isset($chave_nome)){
          $repassa = array("chave_sequencial"=>$chave_sequencial,"chave_nome"=>$chave_nome);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $classinaturaordenadordespesa->sql_record($classinaturaordenadordespesa->sql_query($pesquisa_chave));
          if($classinaturaordenadordespesa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$nome',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_nome",true,1,"chave_nome",true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
