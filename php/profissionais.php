<?php

require_once("common.php");

session_start();
?>


<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <link href="../css/style1.css" rel="stylesheet">
        <title>Pedidos</title>
    </head>
    <style>

.testNewDiv{
  text-decoration:line-through;
}

#person {
  width: 200px;
}

#ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

#list {
  font: 200 20px/1.5 Helvetica, Verdana, sans-serif;
  border-bottom: 1px solid #ccc;
}

li :last-child {
  border: none;
}

.back {
    background: rgba(0, 88, 92, 0.8);
    padding:15px;
}

.box{
    margin: auto;
    width: 60%;
    border: 3px outset gray;
    background-color: lightgray;
}

.barra{
	border-left: 2px solid black;
 	height: 50px;
  margin-left:5px;
  margin-right:5px;
  display:inline-block;
  vertical-align: bottom;
}

.texts{
  margin-left: 10px;
  margin-right: 10px;
  font-size: 20px;
  vertical-align: center;
  display:inline-block;
  font-family: "Roboto", sans-serif;
}

.profButton{
  display:inline-block;
  font-family: "Roboto", sans-serif;
}

.profButton{
  font-family: "Roboto", sans-serif;
  display:inline-block;
  text-transform: uppercase;
  outline-color: 0;
  background: #4caf50;
  border: 0;
  padding: 10px;
  margin: 7px;
  color: #ffffff;
  font-size: 14px;
  cursor: pointer;
}

.profButton:hover,.profButton:active{
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

th{
  background: #a1a1a1;
}

    </style>

    <body>
    <script src="../js/navbarConf.js"></script>
        <script src="../js/pedidos.js" type="text/javascript"></script>

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
  <script>
  function DoSubmit(){
  document.myform.myinput.value = '1';
  return true;
}
  </script>
        <div class="profissionais">
          <form method="POST" onChange="submit();">
          <select name="especialidade" id="selectCatProf"  class="selectCatProf">
            <option value='0'>Escolher categoria</option>
            <option value="'canalizacao'">Canalizador</option>
            <option value="'carpintaria'">Carpinteiro</option>
            <option value="'eletrecidade'">Eletricista</option>
            <option value="'construcao civil'">Construção Civil</option>
          </select>
        </form>
        <?php

    if (isset($_REQUEST["especialidade"])){
          $especialidadea = $_REQUEST["especialidade"];

          if ($especialidadea != '0') {
            $stmt = doQuery("SELECT  area.nome AS areaNome , pessoa_id, equipamento, area_id, pessoa.nome, preco_hora , avaliacao
            FROM profissional, pessoa, area
            WHERE pessoa.id=profissional.pessoa_id 
            AND area.nome=$especialidadea
            AND area.id=profissional.area_id");
            $Nresolt1 = mysqli_num_rows($stmt);
            ?>
            <table border="1" cellpadding="2" cellspacing="2" class="tabela">
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
            ?>
            <tr>
              <td><a href="pedidosPrivados.php?areaId=<?php echo $stmtver['area_id'] ?>&areaNome=<?php echo $stmtver['areaNome'] ?>&proId=<?php echo $stmtver['pessoa_id'] ?>" value=""> <?php echo $stmtver["nome"];?> </a></td>
              <td><?php echo $stmtver["nome"];?></td>
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
            ?>
            </table>
            <?php

          } else {
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
          ?>
        </div>

    </body>
</html>
