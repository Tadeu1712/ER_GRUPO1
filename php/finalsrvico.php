<!DOCTYPE html>

<?php
  //session_start();
  //Quando o site tiver conectado para verificar se o login esta efectuado, NAO ESQUECER DE INSERIR NA BD O ID CERTO!!!!
  // if(!isset($_SESSION['id']))
  // {
  //     echo "MERDA!!!!";
  // }
?>

<html lang="pt">
    <head>
        <meta charset="utf-8">
        <title>Pedidos</title>
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
            <div class="dropdown">
          <li onclick="pedidosDropDown()" class="dropbtn">PEDIDOS</li>
          <div id="dropDownPedidos" class="dropdown-content">
              <a href="pedidosPublicos.php">PUBLICO</a>
              <a href="profissionais.php">DIRETO</a>
          </div>
            </div>

          <li class=""> <a href="#">SOBRE</a> </li>
          <li class=""> <a onclick="return PopComunication('http:/\/localhost:3000/')" href="#">CONTACTO</a> </li>
          <li class=""> <a href="#">

          </a> </li>
        </ul>
      </div>
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
        <form action="" method="post" class="formPedido" id="form1" name="form1" onsubmit="return verificacao()" enctype="multipart/form-data">
        <h2  style="margin-top: 5px; color: white; font-size:35px; text-align: center; text-shadow: 1px 2px grey; " >FACTURAÇÃO</h2>
              <div>
              <p style="font-size: 15px; color: white">Custo total da obra: </p>

                <input placeholder="Custo total da obra " type="text" id="custo" name="custo">
              </div>
            <div>
            <p style="font-size: 15px; color: white">Equipamento: </p>

                <input placeholder="Equipamento" type="text"  name="equipamento" >
            </div>
              <div>
              <p style="font-size: 15px; color: white">Horas total: </p>

                <input placeholder="Horas total" type="text"  name="horas">
              </div>
            <div>
            <p style="font-size: 15px; color: white">PDF: </p>

                <input type="file"  name="pdf"> 
            </div>
            <div>
                <input type="hidden" name="estado" value="inserirDadosBD"/>
                <input  style="background-color:rgba(34, 90, 195, 0.86); border-radius:10px 10px 10px 10px;" value = "Submeter pedido" type="submit">
            </div>
          </form>
          </body>
</html>

          <?php
}
else if($state=="inserirDadosBD"){

    //Valores para inserir da base de dados
    $custo=$_REQUEST["custo"];
    $equipamento=$_REQUEST["equipamento"];
    $hora=$_REQUEST["horas"];
    $idSer = $_REQUEST['idSer'];

    //UPLOAD IMAGEM
    
    $images=$_FILES['pdf'];

    $imageName=$_FILES['pdf']['name'];
    $imageTmpName=$_FILES['pdf']['tmp_name'];
    $imageSize=$_FILES['pdf']['size'];
    $imageError=$_FILES['pdf']['error'];
    $imageType=$_FILES['pdf']['type'];

    $imageExtension=explode('.',$imageName);
    $imageActualExt=strtolower(end($imageExtension));

    $allowed=array('pdf');

    if(in_array($imageActualExt,$allowed)){
      if($imageError===0){

        $imageNewName=uniqid('',true).".".$imageActualExt;
        $imageDestination='../facturas/'.$imageNewName;
        move_uploaded_file($imageTmpName,$imageDestination);

        doQuery("UPDATE `servico` SET `horas` = $hora, `equipamento` = '$equipamento', `custo_total` = $custo, `avaliacao_pro` = 0, `avaliacao_cli` = 0, `inseriu_PDF` = 1, `dir_pdf` = '$imageName' WHERE `servico`.`id` = $idSer;");

        ?>

        
        <div>
          <h1>Serviço finalizado</h1>
        </div>
        
        <?php
        echo "<div class='showData' style='font-size:20px; margin-bottom:10px;'> Dados Inseridos:</div>";
        echo "<div class='showData'> Hora: ".$hora."</div>";
        echo "<div class='showData'> Equipamento: ".$equipamento."</div>";
        echo "<div class='showData'> Custo total: ".$custo."</div>";

        echo '<a href="tabsTemplate.php"><button class="Aceitar"> Confirmar </button></a>';
        }

      }
      else{
        echo "Erro no upload, tente realizar de novo o pedido!";

    
      }
    } 
    
    else{
      echo "Certifique-se que a sua imagem encontra-se no formato pdf!";
    }
  
  
  
?>