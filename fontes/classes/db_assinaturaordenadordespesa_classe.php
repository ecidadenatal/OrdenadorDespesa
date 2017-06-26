<?
//MODULO: acordos
//CLASSE DA ENTIDADE assinaturaordenadordespesa
class cl_assinaturaordenadordespesa {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $sequencial = 0;
   var $departamento = 0;
   var $ativo = 'f';
   var $principal = 'f';
   var $numcgm = null;
   var $titulo = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 sequencial = int4 = Sequencial
                 departamento = int4 = Departamento
                 ativo = bool = Ativo
                 principal = bool = Principal
                 numcgm = int4 = Numero do CGM
                 titulo = varchar(50) = Titulo
                 ";
   //funcao construtor da classe
   function cl_assinaturaordenadordespesa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assinaturaordenadordespesa");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->sequencial   = ($this->sequencial == ""   ? @$GLOBALS["HTTP_POST_VARS"]["sequencial"] : $this->sequencial);
       $this->departamento = ($this->departamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["departamento"] : $this->departamento);
       $this->ativo        = ($this->ativo == "f"       ? @$GLOBALS["HTTP_POST_VARS"]["ativo"] : $this->ativo);
       $this->principal    = ($this->principal == "f"   ? @$GLOBALS["HTTP_POST_VARS"]["principal"] : $this->principal);
       $this->numcgm       = ($this->numcgm == ""         ? @$GLOBALS["HTTP_POST_VARS"]["numcgm"] : $this->numcgm);
       $this->titulo       = ($this->titulo == ""       ? @$GLOBALS["HTTP_POST_VARS"]["titulo"] : $this->titulo);
     }else{
       $this->sequencial   = ($this->sequencial == ""   ? @$GLOBALS["HTTP_POST_VARS"]["sequencial"]:$this->sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($sequencial){
      $this->atualizacampos();
     if($this->departamento == null ){
       $this->erro_sql = " Campo Departamento não informado.";
       $this->erro_campo = "departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ativo == null ){
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->principal == null ){
       $this->erro_sql = " Campo Principal não informado.";
       $this->erro_campo = "principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->numcgm == null ){
       $this->erro_sql = " Campo Numero do CGM não informado.";
       $this->erro_campo = "numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->titulo == null ){
       $this->erro_sql = " Campo Título não informado.";
       $this->erro_campo = "titulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sequencial == "" || $sequencial == null ){
       $result = db_query("select nextval('plugins.assinaturaordenadordespesa_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: assinaturaordenadordespesa_sequencial_seq do campo: sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from plugins.assinaturaordenadordespesa_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $sequencial)){
         $this->erro_sql = " Campo sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sequencial = $sequencial;
       }
     }
     if(($this->sequencial == null) || ($this->sequencial == "") ){
       $this->erro_sql = " Campo sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into plugins.assinaturaordenadordespesa(
                                       sequencial
                                      ,departamento
                                      ,ativo
                                      ,principal
                                      ,titulo
                                      ,numcgm
                       )
                values (
                                $this->sequencial
                               ,$this->departamento
                               ,'$this->ativo'
                               ,'$this->principal'
                               ,'$this->titulo'
                               ,$this->numcgm
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "assinaturaordenadordespesa ($this->sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "assinaturaordenadordespesa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "assinaturaordenadordespesa ($this->sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);

     return true;
   }
   // funcao para alteracao
   public function alterar ($sequencial=null, $sWhere = null) {
      $this->atualizacampos();
     $sql = " update plugins.assinaturaordenadordespesa set ";
     $virgula = "";
     if(trim($this->sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sequencial"])){
       $sql  .= $virgula." sequencial = $this->sequencial ";
       $virgula = ",";
       if(trim($this->sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["departamento"])){
       $sql  .= $virgula." departamento = $this->departamento ";
       $virgula = ",";
       if(trim($this->departamento) == null ){
         $this->erro_sql = " Campo Departamento não informado.";
         $this->erro_campo = "departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ativo"])){
       $sql  .= $virgula." ativo = '$this->ativo' ";
       $virgula = ",";
       if(trim($this->ativo) == null ){
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["principal"])){
       $sql  .= $virgula." principal = '$this->principal' ";
       $virgula = ",";
       if(trim($this->principal) == null ){
         $this->erro_sql = " Campo Principal não informado.";
         $this->erro_campo = "principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["numcgm"])){
       $sql  .= $virgula." numcgm = $this->numcgm ";
       $virgula = ",";
       if(trim($this->numcgm) == null ){
         $this->erro_sql = " Campo Numero do CGM não informado.";
         $this->erro_campo = "numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->titulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["titulo"])){
       $sql  .= $virgula." titulo = '$this->titulo' ";
       $virgula = ",";
       if(trim($this->titulo) == null ){
         $this->erro_sql = " Campo Título não informado.";
         $this->erro_campo = "titulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";

     if($sequencial!=null){
       $sql .= " sequencial = $this->sequencial";
     }

     if (!empty($sWhere)) {
       $sql .= $sWhere;
     }

     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "assinaturaordenadordespesa não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "assinaturaordenadordespesa não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($sequencial=null,$dbwhere=null) {


     $sql = " delete from plugins.assinaturaordenadordespesa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " sequencial = $sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "assinaturaordenadordespesa não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "assinaturaordenadordespesa não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:assinaturaordenadordespesa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from plugins.assinaturaordenadordespesa ";
     $sql .= "      inner join cgm        on  cgm.z01_numcgm = assinaturaordenadordespesa.numcgm";     
     $sql .= "      inner join db_depart  on  db_depart.coddepto = assinaturaordenadordespesa.departamento";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sequencial)) {
         $sql2 .= " where assinaturaordenadordespesa.sequencial = $sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from plugins.assinaturaordenadordespesa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sequencial)){
         $sql2 .= " where assinaturaordenadordespesa.sequencial = $sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

  public function sql_query_assinatura ($sCampos, $sWhere) {

    $sSqlAssinatura  = "select {$sCampos}";
    $sSqlAssinatura .= "  from plugins.documentoassinaturaordenadordespesa doc";
    $sSqlAssinatura .= "       inner join  plugins.assinaturaordenadordespesa ass on doc.assinaturaordenadordespesa = ass.sequencial";
    $sSqlAssinatura .= "       inner join  cgm on cgm.z01_numcgm = ass.numcgm";
    $sSqlAssinatura .= " where {$sWhere}";
    return $sSqlAssinatura;
  }

}
