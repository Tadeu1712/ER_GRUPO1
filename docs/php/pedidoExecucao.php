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
        <title>Pedido Execução</title>
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
  background-color:rgba(206, 206, 206, 0.58);
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
        <form action="" method="post" class="formPedido" id="form2" name="form2" onsubmit="return verificacaoP()" enctype="multipart/form-data">
            <div>
            <h2  style="margin-top: 5px; color: white; font-size:35px; text-align: center; text-shadow: 1px 2px grey; " >Pedido Execução</h2>
            </div>
            <div>
            <p style="font-size: 15px; color: white">Morada: </p>

                <input style=" border:1px solid white; margin-bottom: 15px;" placeholder="Morada" type="text" id="morada" name="morada">
              </div>
              <div>
              <p style="font-size: 15px; color: white">Data Limite: </p>

                <input style=" border:1px solid white; margin-bottom: 15px;" placeholder="Data Limite para propostas formato: aaaa/mm/dd" type="date"  name="dataLimiteInput">
              </div>
            <div>
            <p style="font-size: 15px; color: white">Descrição: </p>

                <textarea style=" border:1px solid white; margin-bottom: 15px;" placeholder="Descrição" rows="4" cols="50" name="descricaoInput" form="form2"></textarea> 
            </div>
            <div>
                <?php 
                    $query_areas=doQuery("SELECT * FROM area");
                    $nr_result_area= mysqli_num_rows($query_areas);
                    for($i=0; $i<$nr_result_area; $i++)
                    {
                        $area_row=mysqli_fetch_assoc($query_areas);
                        ?>
                        <div style="text-transform: capitalize;">
                            <input  style ="margin:0; width:30px;" type="checkbox" name= <?php echo $area_row['id'];  ?> name= <?php echo $area_row['id']; ?>value= <?php echo $area_row['id']; ?>><?php echo $area_row['nome']; ?>
                          
                        </div>
                        <?php
                    }
                ?>
            </div>
            <div>
            <p style="font-size: 15px; color: white">Picture: </p>

                <input style=" border:1px solid white; margin-bottom: 15px;" type="file"  name="picture"> 
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
    $morada=$_REQUEST["morada"];
    $dataLim=$_REQUEST["dataLimiteInput"];
    $descricao=$_REQUEST["descricaoInput"];
    $id_cliente=  $_SESSION['id'];
    //UPLOAD IMAGEM
    
    $images=$_FILES['picture'];

    $imageName=$_FILES['picture']['name'];
    $imageTmpName=$_FILES['picture']['tmp_name'];
    $imageSize=$_FILES['picture']['size'];
    $imageError=$_FILES['picture']['error'];
    $imageType=$_FILES['picture']['type'];

    $imageExtension=explode('.',$imageName);
    $imageActualExt=strtolower(end($imageExtension));

    $allowed=array('jpg','jpeg','png');

    if(in_array($imageActualExt,$allowed)){
      if($imageError===0){

        $imageNewName=uniqid('',true).".".$imageActualExt;
        $imageDestination='../fotosPedidos/'.$imageNewName;
        move_uploaded_file($imageTmpName,$imageDestination);
        
        
        if(isset($_REQUEST['1'])||isset($_REQUEST['2'])||isset($_REQUEST['3'])||isset($_REQUEST['4'])){
            doQuery("INSERT INTO `execucao` (`morada`, `descricao`, `foto`, `dataLimite`, `pessoa_id`)
            VALUES ( '$morada', '$descricao', '$imageNewName', '$dataLim', $id_cliente)");
             $id_query=doQuery("SELECT id FROM execucao WHERE id=(SELECT max(id) FROM execucao)");
             $id_ver=mysqli_fetch_assoc($id_query);
             $id_execucao=$id_ver["id"];
        }
            
        $query_areas=doQuery("SELECT * FROM area");
        $nr_result_area= mysqli_num_rows($query_areas);
        for($i=1; $i<=$nr_result_area; $i++){

        
        if(isset($_REQUEST[$i])){
            doQuery("INSERT INTO `area_subcontratacao` (`execucao_id`, `area_id`)
            VALUES ( $id_execucao, $i)");
          }
        }   

        
        
        ?>


        <div>
          <h1>Seu pedido foi realizado com Sucesso</h1>
        </div>
        
        <?php


        echo "<div ><button class='buttonBack'> Voltar Homepage</button></div>";
        }

      }
      else{
        echo "Erro no upload, tente realizar denovo o pedido!";

    
      }
    } 
    
    else{
      echo "Certifique-se que a sua imagem encontra-se no formato jpg,jpeg ou png!";
    }
//}
  
  
?>