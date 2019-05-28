<!DOCTYPE html>

<?php
  session_start();
  //Quando o site tiver conectado para verificar se o login esta efectuado, NAO ESQUECER DE INSERIR NA BD O ID CERTO!!!!
//    if(!isset($_SESSION['id']))
//    {
//        echo "erro variavel de sessão!!!!";
//    }
//    else{
?>

<html lang="pt">
    <head>
        <meta charset="utf-8">
        <title>Proposta</title>
        <link href="../css/style1.css" rel="stylesheet">
        <style>
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
  width: 300px;
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

.subButton{
  border:1px;
}

input, textarea{
  font-family: "Roboto", sans-serif;
  outline: 1;
  background: #f2f2f2;
  width: 400px;
  border: 0;
  margin: 0 0 15px;
  padding: 10px;
  box-sizing: border-box;
  font-size: 14px;
  background:rgba(206, 206, 206, 0.58);
}

::placeholder{
  color: grey;
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
        <?php
          if (isset($_SESSION['tipo'])&&($_SESSION['tipo'] == 'cliente' || $_SESSION['tipo'] == 'empresa') ) {
                    echo '<div class="dropdown">
                  <li onclick="pedidosDropDown()" class="dropbtn">PEDIDOS</li>
                  <div id="dropDownPedidos" class="dropdown-content">
                      <a href="pedidosPublicos.php">PUBLICO</a>
                      <a href="profissionais.php">DIRETO</a>
                      <a href="pedidoExecucao.php">EXECUÇÃO</a>
                  </div>
                    </div>';
                  
          }
            ?>
            <div class="dropdown">
          <li onclick="reclamacaoDropDown()" class="dropbtn">CONTACTO</li>
                  <div id="dropDownReclamacao" class="dropdown-content">
                      <a onclick="return PopComunication('http:/\/localhost:3000/')" href="#">CHAT</a>
                      <a href="reclamacao.php">RECLAMAÇÃO</a>
                  </div>
        </div>
            <div class="dropdown">
          <li onclick="loginDropDown()" class="dropbtn">
          <?php
                echo $_SESSION['name'];
            ?>
            </li>
          <div id="dropDownAccount" class="dropdown-content">
              <a href="tabsTemplate.php">PAINEL DE CONTROLO</a>
              <a href="?logout">LOGOUT</a>

          </div>

        </ul>
      <!--</div>-->
      <br><br><br><br><br><br><br><br><br><br><br>
      <div class="">

      <?php
require_once("common.php");

$state=null;

if(isset($_POST["estado"])){
$state=$_POST["estado"];
}

if( $state==null ){
    ?>

        <script src="../js/verificationPedido.js" type="text/javascript"></script>
        <form  method="post" class="formPedido" id="formProp" name="formProp" onsubmit="return verificacaoProp()" enctype="multipart/form-data">
            <div>
                
                <h2 style="margin-top: 5px; color: white; font-size:35px; text-align: center; text-shadow: 1px 2px grey; " >Proposta</h2>
            <div>
             <div>
             <p style="font-size: 15px; color: white; ">Orçamento:</p>
                <input   style=" border:1px solid white; margin-bottom: 15px; " type="number" step="0.01" id="custo" name="custo">
              </div>
              <div>
                <p style="font-size: 15px; color: white">Data inicio do serviço: </p>
                <input  style=" border:1px solid white; margin-bottom: 15px;" type="date"  name="dataInicio" min = "<?php echo date(d/m/y);?>">
            </div>
            <div >
                <p style="font-size: 18px; color: white">Data finalização do Serviço:</p>

                <input style=" border:1px solid white;" type="date"  name="dataFim">
            </div>
            <section>
            <div style=" border:1px solid white; margin-bottom: 15px;">
            <p style="font-size: 15px; color: white">Homens Hora: </p>
                <input type="int" name="homem_hora" />
            </div>
            </section>

            <section>
              <div class="subButton">
                  <input type="hidden" name="estado" value="propostaEnviada">
                  <input style="background-color:rgba(34, 90, 195, 0.86); border-radius:10px 10px 10px 10px;" value = "Submeter proposta" type="submit">
              </div>
            </section>
          </form>
          <!--</div>-->
          </body>
</html>

          <?php
}
else if($state=="propostaEnviada"){
    $custo_proposta = $_REQUEST['custo'];
    $dataIn=$_REQUEST["dataInicio"];
    $dataFim=$_REQUEST["dataFim"];
    $idPedido=$_REQUEST['idPed'];
    $idPro=$_SESSION['id'];
    echo "Orçamento: ".$custo_proposta."€<br>";

    if(!($_REQUEST['homem_hora']==0)){
        $homemHora=$_REQUEST['homem_hora'];

        doQuery(" INSERT  INTO `proposta` (`custo_total`, `homens_hora`,`data_inicio`,`data_fim`,`pedido_id`,`profissional_pessoa_id`)
         VALUES ($custo_proposta, $homemHora,'$dataIn','$dataFim',$idPedido,$idPro)");
    }
    else{
        echo "Não foi escolhido profissional auxiliar";

        doQuery(" INSERT  INTO `proposta` (`custo_total`,`data_inicio`,`data_fim`,`pedido_id`,`profissional_pessoa_id`)
         VALUES ($custo_proposta,'$dataIn','$dataFim',$idPedido,$idPro)");
    }
}
//}
  
  
?>