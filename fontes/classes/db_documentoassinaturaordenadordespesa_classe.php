<?
//MODULO: empenho
//CLASSE DA ENTIDADE documentoassinaturaordenadordespesa
class cl_documentoassinaturaordenadordespesa { 
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
   var $tipo = null; 
   var $chave = null; 
   var $assinaturaordenadordespesa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sequencial = int4 = Sequencial 
                 tipo = varchar(50) = Tipo 
                 chave = varchar(200) = Chave Primária 
                 assinaturaordenadordespesa = int4 = Ordenador da Depesa 
                 ";
   //funcao construtor da classe 
   function cl_documentoassinaturaordenadordespesa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("documentoassinaturaordenadordespesa"); 
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
       $this->sequencial = ($this->sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sequencial"]:$this->sequencial);
       $this->tipo = ($this->tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["tipo"]:$this->tipo);
       $this->chave = ($this->chave == ""?@$GLOBALS["HTTP_POST_VARS"]["chave"]:$this->chave);
       $this->assinaturaordenadordespesa = ($this->assinaturaordenadordespesa == ""?@$GLOBALS["HTTP_POST_VARS"]["assinaturaordenadordespesa"]:$this->assinaturaordenadordespesa);
     }else{
       $this->sequencial = ($this->sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["sequencial"]:$this->sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($sequencial){ 
      $this->atualizacampos();
     if($this->tipo == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->chave == null ){ 
       $this->erro_sql = " Campo Chave Primária não informado.";
       $this->erro_campo = "chave";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->assinaturaordenadordespesa == null ){ 
       $this->erro_sql = " Campo Ordenador da Depesa não informado.";
       $this->erro_campo = "assinaturaordenadordespesa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sequencial == "" || $sequencial == null ){
       $result = db_query("select nextval('plugins.documentoassinaturaordenadordespesa_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: documentoassinaturaordenadordespesa_sequencial_seq do campo: sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from plugins.documentoassinaturaordenadordespesa_sequencial_seq");
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
     $sql = "insert into plugins.documentoassinaturaordenadordespesa(
                                       sequencial 
                                      ,tipo 
                                      ,chave 
                                      ,assinaturaordenadordespesa 
                       )
                values (
                                $this->sequencial 
                               ,'$this->tipo' 
                               ,'$this->chave' 
                               ,$this->assinaturaordenadordespesa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documentos assinados pelo ordenador ($this->sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documentos assinados pelo ordenador já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documentos assinados pelo ordenador ($this->sequencial) não Incluído. Inclusão Abortada.";
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
   public function alterar ($sequencial=null) { 
      $this->atualizacampos();
     $sql = " update plugins.documentoassinaturaordenadordespesa set ";
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
     if(trim($this->tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tipo"])){ 
       $sql  .= $virgula." tipo = '$this->tipo' ";
       $virgula = ",";
       if(trim($this->tipo) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->chave)!="" || isset($GLOBALS["HTTP_POST_VARS"]["chave"])){ 
       $sql  .= $virgula." chave = '$this->chave' ";
       $virgula = ",";
       if(trim($this->chave) == null ){ 
         $this->erro_sql = " Campo Chave Primária não informado.";
         $this->erro_campo = "chave";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->assinaturaordenadordespesa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["assinaturaordenadordespesa"])){ 
       $sql  .= $virgula." assinaturaordenadordespesa = $this->assinaturaordenadordespesa ";
       $virgula = ",";
       if(trim($this->assinaturaordenadordespesa) == null ){ 
         $this->erro_sql = " Campo Ordenador da Depesa não informado.";
         $this->erro_campo = "assinaturaordenadordespesa";
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
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos assinados pelo ordenador não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos assinados pelo ordenador não foi Alterado. Alteração Executada.\\n";
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

     $sql = " delete from plugins.documentoassinaturaordenadordespesa
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
       $this->erro_sql   = "Documentos assinados pelo ordenador não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Documentos assinados pelo ordenador não Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:documentoassinaturaordenadordespesa";
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
     $sql .= "  from plugins.documentoassinaturaordenadordespesa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sequencial)) {
         $sql2 .= " where documentoassinaturaordenadordespesa.sequencial = $sequencial "; 
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
     $sql .= "  from plugins.documentoassinaturaordenadordespesa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($sequencial)){
         $sql2 .= " where documentoassinaturaordenadordespesa.sequencial = $sequencial "; 
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

}
