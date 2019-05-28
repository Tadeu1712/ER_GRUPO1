<!DOCTYPE html>

<?php
  session_start();

?>

<html lang="pt">
    <head>
        <meta charset="utf-8">
        <title>Pedido Publico</title>
        <link href="../css/style1.css" rel="stylesheet">
        <style>
          .dropbtn {
  background-color: #3498DB;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
  background-color: #2980B9;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  overflow: auto;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}

        form {
  /* Just to center the form on the page */
  margin: 0 auto;
  width: 400px;
  /* To see the outline of the form */
  padding: 1em;
  border: 1px solid #CCC;
  border-radius: 1em;
}

h1 {
  border: 1px dotted black;
  background: white;
  text-align:center;
}



.showData{
  padding-left: 10px;
  padding-right: 10px;
  color: white;
}

form div + div {
  margin-top: 1em;
}

label {
  /* To make sure that all labels have the same size and are properly aligned */
  display: inline-block;
  width: 90px;
  text-align: right;
}

 textarea {
  /* To make sure that all text fields have the same font settings
     By default, textareas have a monospace font */
  font: 1em sans-serif;

  /* To give the same size to all text fields */
  width: 500px;
  box-sizing: border-box;

  /* To harmonize the look & feel of text field border */
  border: 1px solid #999;
}

input:focus, textarea:focus {
  /* To give a little highlight on active elements */
  border-color: #000;
}

textarea {
  /* To properly align multiline text fields with their labels */
  vertical-align: top;

  /* To give enough room to type some text */
  height: 5em;
}

input, textarea{
  font-family: "Roboto", sans-serif;
  outline: 1;
  background: #f2f2f2;
  width: 400px;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}



.button{
  margin-left: 602px;
  margin-top: 15px;
}

.button button{
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline-color: 0;
  background: #4caf50;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #ffffff;
  font-size: 14px;
  cursor: pointer;
  background-color:rgba(206, 206, 206, 0.58);

}

::placeholder{
  color: grey;
}

.buttonBack{
  margin: 20px;
}

.button button:hover, .button button:active{
  background: #43a047;
}

        </style>
    </head>
    <body>
      <script src="../js/navbarConf.js"></script>
      <div class="row">
        <div class="logo">
          <a href="welcome.php"> <img src="../img/logo.png"></a>
        </div>

        <ul class="main-nav">

          <li class=""> <a href="#">SOBRE</a> </li>
          <li class=""> <a onclick="return PopComunication('http:/\/localhost:3000/')" href="#">CONTACTO</a> </li>
          <div class="dropdown">
          <li onclick="loginDropDown()" class="dropbtn">
          <?php
                echo $_SESSION['name'];
            ?>
            </li>
          <div id="dropDownAccount" class="dropdown-content">
          <a href="tabsTemplate.php">PAINEL DE CONTROLO</a>
              <a href="?logout">LOGOUT</a>

          </a> </li>
        </ul>
      </div>
      <br><br><br><br><br><br><br><br><br><br><br>
      <div class="">

      <?php
require_once("common.php");

$state=null;



if($_SESSION['tipo'] == 'cliente'||$_SESSION['tipo'] == 'empresa')
{
    
    $queryRec ="SELECT pessoa.nome,pessoa.id FROM pedido, pessoa
    WHERE pedido.pessoa_id='{$_SESSION['id']}' 
    AND pedido.profissional_pessoa_id=pessoa.id";
  
}
else
{
    $queryRec ="SELECT pessoa.nome,pessoa.id FROM pedido, pessoa
    WHERE pedido.pessoa_id=pessoa.id 
    AND pedido.profissional_pessoa_id='{$_SESSION['id']}'";
 
}
if(isset($_POST["estado"])){
$state=$_POST["estado"];
}

if( $state==null ){

    $pedidos=doQuery($queryRec);

    $nPedidos=mysqli_num_rows($pedidos);
    ?>

        <script src="../js/verificationPedido.js" type="text/javascript"></script>
        <form action="" method="post" class="formPedido" id="form1" name="form1" onsubmit="return verificacao()" enctype="multipart/form-data">
            <div>
            <h2 style="margin-top: 5px; color: white; font-size:35px; text-align: center; text-shadow: 1px 2px grey; " >RECLAMAÇÃO</h2>
                <select name="profissional"  class="selectCatProf">
                    <option value=0>Escolher a Área</option>
                    <?php

                    for ($i=0; $i < $nPedidos; $i++) {
                        $pedidosver=mysqli_fetch_assoc($pedidos);
                       ?><option value=<?php echo $pedidosver['id']; ?>><?php echo $pedidosver['nome']; ?></option><?php
                    }
                    ?>
                </select>
            </div>
            <div>
                <textarea placeholder="reclamação" rows="4" cols="50" name="reclamacao" form="form1"></textarea> 
            </div>
            <div>
                <input type="hidden" name="estado" value="inserirDadosBD"/>
                <input value = "Submeter pedido" type="submit">
            </div>
          </form>
          </body>
</html>

          <?php
}
else if($state=="inserirDadosBD"){
       
      
        $idProfissional=$_REQUEST["profissional"];
      

      if($_SESSION['tipo'] == 'cliente'||$_SESSION['tipo'] == 'empresa')
      {
          
            $insertRec="SELECT pedido.id, pedido.descricao FROM pedido, pessoa 
            WHERE pedido.profissional_pessoa_id=$idProfissional";
          
      }
      else
      {
         
            $insertRec="SELECT pedido.id, pedido.descricao FROM pedido, pessoa 
            WHERE pedido.pessoa_id=$idProfissional";
          
      }
    
    //Valores para inserir da base de dados
    
      $reclamacao=$_REQUEST["reclamacao"];

        $select=mysqli_fetch_assoc(doQuery($insertRec));

        doQuery("INSERT  INTO `reclamacao` (`pedido_id`,`pessoa_id`,`reclamacao`) 
                VALUES ('{$select['id']}', '{$_SESSION["id"]}', '$reclamacao')");
      
        ?>


        <div>
          <h1>Seu pedido foi realizado com Sucesso</h1>
        </div>
        
        <?php

        echo "<div class='showData' style='font-size:20px; margin-bottom:10px;'> Dados Inseridos:</div>";
        echo "<div class='showData'> Descrição do pedido: ".$select['descricao']."</div>";
        echo "<div class='showData'> Morada: ".$reclamacao."</div>";

        echo "<div ><button class='buttonBack'> Voltar Homepage</button></div>";
      } else {
            echo "Certifique-se que a sua imagem encontra-se no formato jpg,jpeg ou png!";
        }
    
?>