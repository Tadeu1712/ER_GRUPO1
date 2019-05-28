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
          width: 90%;
          margin: 20px;
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

.tabela{
  text-align: left;
  width: 90%;
  margin: 20px;
  background-color: #D1CBD4;
  border-style: outset;
  border-width: 2px;
  border-color: gray;
}

td, th{
  border-style: outset;
  border-width: 2px;
  border-color: rgba(0, 88, 92, 0.8);;
  text-align: center;
  text-align: center;
  font-size: 18px;
  font-family: "Roboto", sans-serif;
}
.Aceitar{
    	padding: 2px 10px;
      background: #1aff1a;
      border: 2px solid #00b300;
      margin-right: 15px;
      font-size: 16px;
      border-radius: 4px;
    }

    .Aceitar:hover{
    	 background:#80ff80;
    }
th{
  background: #a1a1a1;
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

$area=null;

if(isset($_POST["area"])){
$area=$_POST["area"];
}

if(!isset($_REQUEST['flagExe']))
{   
    $id_pedido_exe=$_REQUEST['execId'];

    $dados_pedido_ver=mysqli_fetch_assoc(doQuery("SELECT * FROM execucao WHERE id=$id_pedido_exe"));

    $dados_pedido_exec_areas=
      doQuery("SELECT area.id, area.nome  
              FROM area_subcontratacao, area 
              WHERE area_subcontratacao.area_id=area.id AND area_subcontratacao.execucao_id=$id_pedido_exe");
    $_SESSION['exec_id']= $id_pedido_exe;
    $num_areas_ped_exec=mysqli_num_rows($dados_pedido_exec_areas);
    if($num_areas_ped_exec!=0)
    {
    echo "<form method='POST'>
    <select name='area' id='area'  style='width:100%' onChange='submit();' > 
    <option value='0'>Escolher categoria</option>";
    for($i=0; $i<$num_areas_ped_exec; $i++){
      $dados_area=mysqli_fetch_assoc($dados_pedido_exec_areas);
        echo "<option value=".$dados_area["id"].">".$dados_area["nome"]."</option>";
    }
    
    echo "</select>

    </form>";
  
 
  if(isset($_REQUEST['area'])){
    $especialidadea=$_REQUEST["area"];
    


      if ($especialidadea != '0') {
        $dados_pedido_ver=mysqli_fetch_assoc(doQuery("SELECT * FROM execucao WHERE id=$id_pedido_exe"));
        

        $stmt = doQuery("SELECT  (area.nome) as areaNome , pessoa_id, equipamento, area_id, pessoa.nome, preco_hora ,avaliacao
        FROM profissional, pessoa, area
        WHERE pessoa.id=profissional.pessoa_id 
        AND area.id=$especialidadea
        AND area.id=profissional.area_id");
        $Nresolt1 = mysqli_num_rows($stmt);
        ?>
        <table style="border: 1" cellpadding="2" cellspacing="2" class="tabela">
          <tr>
            <th><b> Nome </b></th>
            <th><b> Especialzação </b></th>
            <th><b> Avaliação </b></th>
            <th><b> Preço/Hora </b></th>
            <th><b> Perfil </b></th>

          </tr>
        <?php
        for ($i = 0; $i < $Nresolt1; $i++) {
          $stmtver = mysqli_fetch_assoc($stmt);
           $idPessoa=  $stmtver['pessoa_id'] ;
        ?>
        <tr>
          <td><a href="?flagExe=<?php echo $id_pedido_exe;?>&areaId=<?php echo $_REQUEST['area'];?>&proId=<?php echo  $idPessoa ;?>" > <?php echo $stmtver["nome"];?> </a></td>
          <td><?php echo $stmtver["areaNome"];?></td>
          <td><?php echo $stmtver["avaliacao"];?></td>
          <td><?php echo $stmtver["preco_hora"];?></td>
          <td> <button type="button" class="profButton">Ver Perfil</button> </td>
        </tr>

        <!-- <div class="person">
          <ul class="ul">
              <li class="list"> <a href="../html/pedidos.html"><?php //echo $stmtver["person_id"]; echo " "; echo $stmtver["name"];?> </a></li>
          </ul>
        </div> -->
        
      <?php
      
        }
        echo " </table>";
      }
      else {
        $naoselecionado = doQuery("SELECT pessoa_id, equipamento, area FROM profissional");
        $Nresolt = mysqli_num_rows($naoselecionado);

        for ($i = 0; $i < $Nresolt; $i++) {
          $naoSelecionadoVer = mysqli_fetch_assoc($naoselecionado);
          ?>
              <ul class="ul">
                  <li class="list"> <a href="../html/pedidos.html" >
                  <?php echo $naoSelecionadoVer["pessoa_id"]; echo " "; echo $naoSelecionadoVer["area"];?>
                  </a>
                  </li>
              </ul>
            </div>
          <?php

        }
      }
    }
    
  }
  else
  {
    echo '<h2 style="margin-top: 5px; color: white; font-size:35px; text-align: center; text-shadow: 4px 3px 10px rgba(0,0,0,0.95); "> Proposta de execução resolvid</h2>
    <a href="tabsTemplate.php"><button class="Aceitar">Confirmar</button></a>';

    $id_pedido_exe =$_SESSION['exec_id'];

    doQuery("DELETE FROM execucao WHERE id = ' $id_pedido_exe' ");

    unset($_SESSION['exec_id']);

  }
}
else
{
  
  $id_pedido_exe =$_REQUEST['flagExe'];
    
  $dados_pedido_ver=mysqli_fetch_assoc(doQuery("SELECT * FROM execucao WHERE id=$id_pedido_exe"));
  $area = $_REQUEST['areaId'];
  $morada=$dados_pedido_ver['morada'];
  $descricao=$dados_pedido_ver['descricao'];
  $imageNewName=$dados_pedido_ver['foto'];
  $id_cliente=$dados_pedido_ver['pessoa_id'];
  $idPro=$_REQUEST['proId'];


  doQuery("INSERT  INTO `pedido` (`area_id`,`morada`,`descricao`,`dataLimite`,`aceita_proposta`,`foto`,`pessoa_id`,`profissional_pessoa_id`) 
      VALUES ('$area', '$morada', '$descricao','$dataLim', 0,'$imageNewName',$id_cliente,$idPro)");

  doQuery("DELETE FROM area_subcontratacao WHERE area_id = '$area' AND execucao_id= '$id_pedido_exe'");

  unset($_REQUEST['flagExe']);
  unset($_REQUEST['areaId']);
  unset($_REQUEST['proId']);

  header("Location: resolveExecucao.php?execId=$id_pedido_exe");
}
  ?>
            </div>

</body>
</html>



  
  
