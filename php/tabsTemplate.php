<?php
session_start();

require_once('common.php');
require_once('socialConfig.php');

if(isset($_REQUEST['logout']))
{
  unset($_SESSION['accessToken']);
  unset($_SESSION['loggedin']);
  unset($_SESSION['name']);
  unset($_SESSION['id']);
  unset($_SESSION['userData']);
  header('Location: login.php');
  exit();
}

 if(!isset($_SESSION['name']) && isset($_SESSION['userData']))
 {
   $_SESSION['name']=$_SESSION['userData']['first_name'] . ' ' . $_SESSION['userData']['last_name'];
 }

$con = connection();

//apagar um certo pedido porque o profissional nao aceitou a proposta
if(isset($_REQUEST['eidProp']))
{
  if($stmt = $con->prepare("DELETE FROM proposta WHERE id = ?"))
  {
    $stmt->bind_param('i', $_REQUEST['eidProp']);
    $stmt->execute();
  if($stmt = $con->prepare("DELETE FROM pedido WHERE id = ?"))
  {
    $stmt->bind_param('i', $_REQUEST['idPed']);
    $stmt->execute();
    $stmt->close();
  }
    unset($_REQUEST['eidProp']);
    unset($_REQUEST['idPed']);
    header('Location: tabsTemplate.php');
    exit();
  }
}

//aceitar um certo pedido 
if(isset($_REQUEST['aidProp']))
{
  if($stmt = $con->prepare("SELECT profissional_pessoa_id FROM proposta WHERE id=?"))
  {
    $stmt->bind_param('i', $_REQUEST['aidProp']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($idPro);
    $stmt->fetch();

    if($stmt = $con->prepare("UPDATE `pedido` SET `profissional_pessoa_id` = ? WHERE `pedido`.`id` = ?"))
  {
      $stmt->bind_param('ii',$idPro, $_REQUEST['idPed']);
      $stmt->execute(); 
  }
  }



  if($stmt = $con->prepare("UPDATE `pedido` SET `aceita_proposta` = '1' WHERE `pedido`.`id` = ?"))
  {
    $stmt->bind_param('i', $_REQUEST['idPed']);
    $stmt->execute();
  if($stmt = $con->prepare("INSERT INTO `servico` (`id`, `pedido_id`, `horas`, `equipamento`, `custo_total`, `avaliacao_pro`, `avaliacao_cli`, `inseriu_PDF`, `dir_pdf`) VALUES (NULL, ?, '0', NULL, '0', '0', '0', '0', NULL)"))
  {
      $stmt->bind_param('i', $_REQUEST['idPed']);
      $stmt->execute(); 
  }
    unset($_REQUEST['aidProp']);
    header('Location: tabsTemplate.php');
    exit();
  }
  $stmt->close();
}



//verifica o tipo de pessoa que fez login
if(isset($_SESSION['id']))
{
  if($stmt = $con->prepare("SELECT tipo FROM pessoa WHERE id= ? "))
  {
    $id=$_SESSION['id'];
    //insere o id na expressao
    $stmt->bind_param('i', $id);
    $stmt->execute();
    //guarda os resultados
    $stmt->store_result();

    $stmt->bind_result($personType);
    $stmt->fetch();
  }
  $stmt->close();
}
//apagar uma certa publicidade
if(isset($_REQUEST['deleteBtn']))
{
  if($stmt = $con->prepare("DELETE FROM publicidade WHERE id = ?"))
  {
    $stmt->bind_param('i', $_REQUEST['deleteBtn']);
    $stmt->execute();
    $stmt->close();
    unset($_REQUEST['deleteBtn']);
    header('Location: tabsTemplate.php');
    exit();
  }
}
//mudar url de uma publicidade
if(isset($_REQUEST['changeUrl']))
{
  if($stmt = $con->prepare("UPDATE publicidade SET url=? WHERE id=? "))
  {
    $stmt->bind_param('si', $_REQUEST['newUrl'], $_REQUEST['pubID']);
    $stmt->execute();
    $stmt->close();
    unset($_REQUEST['changeUrl']);
    unset($_REQUEST['newUrl']);
    unset($_REQUEST['pubID']);
    header('Location: tabsTemplate.php');
    exit();
  }
}

//adiciona publicidade na base de dados
if(isset($_REQUEST['addPub']))
{

  if($_REQUEST['areaID']!=0)
  {
    if($stmt = $con->prepare("INSERT INTO `publicidade` (`id`, `url`, `name`, `area_id`) VALUES (NULL, ?, ?, ?)"))
    {
      
      $stmt->bind_param('ssi', $_REQUEST['pubUrl'], $_REQUEST['pubNome'],$_REQUEST['areaID']);
      $stmt->execute();
      $stmt->close();
      unset($_REQUEST['addPub']);
      unset($_REQUEST['pubUrl']);
      unset($_REQUEST['pubNome']);
      unset($_REQUEST['areaID']);
      header('Location: tabsTemplate.php');
      exit();
    }
  }
  else
  {
    echo '<script> alert("Escolha uma area") </script>';
  }
}

//apagar um certo pedido
if(isset($_REQUEST['deletePed']))
{
  if($stmt = $con->prepare("DELETE FROM pedido WHERE id = ?"))
  {
    $stmt->bind_param('i', $_REQUEST['deletePed']);
    $stmt->execute();
    $stmt->close();
    unset($_REQUEST['deletePed']);
    header('Location: tabsTemplate.php');
    exit();
  }
}

//apagar um certo pedido
if(isset($_REQUEST['deleteRec']))
{
  if($stmt = $con->prepare("DELETE FROM reclamacao WHERE id = ?"))
  {
    $stmt->bind_param('i', $_REQUEST['deleteRec']);
    $stmt->execute();
    $stmt->close();
    unset($_REQUEST['deleteRec']);
    header('Location: tabsTemplate.php');
    exit();
  }
}

//inserir avaliacao a um profissional
if(isset($_REQUEST['inserirAvaProBD']))
{
  if($stmt = $con->prepare("UPDATE `servico` SET `avaliacao_pro` = ? WHERE `servico`.`id` =?"))
  {
    $stmt->bind_param('ii', $_REQUEST['avaValue'],$_REQUEST['servicoID']);
    $stmt->execute();
  }
//obter o id do profissional
  if($stmt = $con->prepare("SELECT profissional_pessoa_id,avaliacao FROM pedido,servico,pessoa WHERE profissional_pessoa_id=pessoa.id AND pedido.id=servico.pedido_id AND servico.id=?"))
  {
    $stmt->bind_param('i', $_REQUEST['servicoID']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($proID, $proAva);
    $stmt->fetch();
    if($proAva!=0)
    {
      $proAva+=$_REQUEST['avaValue'];
      $proAva=$proAva/2;
    }
    else
    {
      $proAva+=$_REQUEST['avaValue'];
    }
    //inserir a avaliacao no profissional
    if($stmt = $con->prepare("UPDATE `pessoa` SET `avaliacao` = ? WHERE `pessoa`.`id` = ?"))
    {
      $stmt->bind_param('ii',$proAva, $proID);
      $stmt->execute();
    }
  }
  $stmt->close();
  unset($_REQUEST['inserirAvaProBD']);
  header('Location: tabsTemplate.php');
  exit();

}

//inserir a avaliacao a um cliente
if(isset($_REQUEST['inserirAvaCliBD']))
{
  if($stmt = $con->prepare("UPDATE `servico` SET `avaliacao_cli` = ? WHERE `servico`.`id` =?"))
  {
    $stmt->bind_param('ii',$_REQUEST['avaValue'],$_REQUEST['servicoID']);
    $stmt->execute();
   
  }

  //obter o id do cliente
  if($stmt = $con->prepare("SELECT pessoa_id,avaliacao FROM pedido,servico,pessoa WHERE pessoa_id=pessoa.id AND pedido.id=servico.pedido_id AND servico.id=?"))
  {
    $stmt->bind_param('i', $_REQUEST['servicoID']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($cliID, $cliAva);
    $stmt->fetch();
    echo $cliAva.'<br>'.$cliID;
    if($cliAva!=0)
    {
      $cliAva+=$_REQUEST['avaValue'];
      $cliAva=$cliAva/2;
    }
    else
    {
      $cliAva+=$_REQUEST['avaValue'];
    }
    //inserir a avaliacao no cliente
    if($stmt = $con->prepare("UPDATE `pessoa` SET `avaliacao` = ? WHERE `pessoa`.`id` = ?"))
    {
      $stmt->bind_param('ii',$cliAva, $cliID);
      $stmt->execute();
    }
  }
  $stmt->close();
  unset($_REQUEST['inserirAvaCliBD']);
  header('Location: tabsTemplate.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <link href="../css/style1.css" rel="stylesheet">
    <title>Perfil</title>

    <script src="../js/communication.js" type="text/javascript"></script>
</head>

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

    body {font-family: Arial;}

    /* Style the tab */
    .tab {
      width: 80%;
      margin: auto;
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: rgba(0, 213, 207,0.3);
    }

    /* Style the buttons inside the tab */
    .tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
      font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
      background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
      background-color: rgba(0, 213, 207,0.8);
    }

    /* Style the tab content */
    .tabcontent {
      margin: auto;
      width: 80%;
      display: none;
      padding: 6px 0px;
      border: 1px solid #ccc;
      border-top: none;
      background:rgba(0, 88, 92, 0.8);
    }

    .tabcontent h3{
    	color:lightgray;
    }

    .tabela{
      text-align: left;
      width: 96%;
      margin: 20px;
      background-color: rgba(209, 203, 212, 0.2);
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
      background: rgba(161, 161, 161, 0.2);
    }

    .Descartar{
    	padding: 2px 10px;
      background: #ff1a1a;
      border: 2px solid #b30000;
      font-size: 16px;
      border-radius: 4px;
    }

    .Descartar:hover{
    	 background:#ff8080;
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

    .Fim{
    	padding: 2px 10px;
      background: #1a8cff;
      border: 2px solid #0059b3;
      margin-right: 15px;
      font-size: 16px;
      border-radius: 4px;
    }

    .Fim:hover{
    	 background:#80bfff;
    }

    .Avaliar{
    	padding: 2px 10px;
      background: #ffff1a;
      border: 2px solid #b3b300;
      margin-right: 15px;
      font-size: 16px;
      border-radius: 4px;
    }

    .Avaliar:hover{
    	 background:#ffff80;
    }

    .fl-table {
    border-radius: 5px;
    font-size: 12px;
    font-weight: normal;
    border: none;
    border-collapse: collapse;
    width: 100%;
    max-width: 100%;
    white-space: nowrap;
    background-color: white;
}

.fl-table td, .fl-table th {
    text-align: center;
    padding: 8px;
}

.fl-table td {
    border-right: 1px solid #f8f8f8;
    font-size: 12px;
}

.fl-table thead th {
    color: #ffffff;
    background: rgba(0, 88, 92, 0.5);
}

.fl-table tr:nth-child(odd) {
    background: rgba(255, 255, 255, 0.5);
}

.fl-table tr:nth-child(even) {
    background: rgba(230, 230, 230, 0.5);
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
            <div class="dropdown">
          <li onclick="loginDropDown()" class="dropbtn">
          <?php
                echo $_SESSION['name'];
            ?>
            </li>
          <div id="dropDownAccount" class="dropdown-content">
              <a href="?logout">LOGOUT</a>

        </ul>
      </div>

      <br><br><br><br><br><br><br><br><br>

      <div class="tab">
      <?php
       
        if(isset($personType)&&$personType=='funcionario')
        {
          echo '<button class="tablinks" onclick="openCity(event, '. "'Pedidos_de_execucao'" .')">Pedidos de Execução</button>';
          echo '<button class="tablinks" onclick="openCity(event, '. "'Reclamacoes'" .')">Reclamações</button>';
          echo '<button class="tablinks" onclick="openCity(event, '. "'Publicidade'" .')">Publicidade</button>';
        }
        else if(isset($_SESSION['userData']))
        {
          echo '<button class="tablinks" onclick="openCity(event, '. "'Historico'" .')">Histórico</button>';
        }
        else
        {
          if(isset($personType)&&$personType=='profissional')
          {
            echo '<button class="tablinks" onclick="openCity(event, '. "'Pedidos_publicos'" .')">Pedidos Públicos</button>';
            echo '<button class="tablinks" onclick="openCity(event, '. "'Pedidos_diretos'" .')">Pedidos Diretos</button>';
          }
         
          else
          {
            echo '<button class="tablinks" onclick="openCity(event, '. "'Pedidos'" .')">Pedidos</button>';
          }
          echo '<button class="tablinks" onclick="openCity(event, '. "'Servicos'" .')">Os teus serviços</button>';
          echo '<button class="tablinks" onclick="openCity(event, '. "'Avaliacoes'" .')">Avaliar</button>';
        }
        ?>
      </div>

        <?php
          //formulario para mudar url de publicidade
          if(isset($_REQUEST['changeBtn']))
          {
            if($stmt = $con->prepare("SELECT name,url FROM publicidade WHERE id=?"))
            {
              $stmt->bind_param('i', $_REQUEST['changeBtn']);
              $stmt->execute();
              $stmt->store_result();
              $stmt->bind_result($pubName,$pubURL);
              $stmt->fetch();
              echo 'Mudar Url para a publicidade: ' . $pubName;
              echo '<form action="?changeUrl" method="post">
                    <input type=url placeholder="Insira o novo url" name="newUrl" required/>
                    <input type="hidden" name="pubID" value="'. $_REQUEST['changeBtn'] .'"/>
                    <input type="submit" value="MUDAR URL"/>
                    </form>
                    <a href="tabsTemplate.php"><button class="Descartar">CANCELAR</button></a>';
              unset($_REQUEST['changeBtn']);
              $stmt->close();
            }
          }

          //formulario para adicionar publicidade
          if(isset($_REQUEST['addBtn']))
          {
            if($stmt = $con->prepare("SELECT id,nome FROM area"))
            {
              $stmt->execute();
              $stmt->store_result();
              $area_num_rows=$stmt->num_rows();
              echo 'Adicionar publicidade <br>';
              echo '<form action="tabsTemplate.php?addPub" method="post">
              <select name="areaID" id="selectArea">
              <option value="0">SELECIONE</option>';
            
              //escreve o nome das areas na select box
              for($i=0;$i<$area_num_rows;$i++)
              {
                $stmt->bind_result($idArea,$nomeArea);
                $stmt->fetch();
                echo '<option value="'. $idArea .'">'. $nomeArea .'</option>';
              }
              
              echo '</select>
                    <input type=text placeholder="Insira o nome" name="pubNome" required/>
                    <input type="url" name="pubUrl" placeholder="Insira o url" required/>
                    <input type="submit" value="INSERIR PUBLICIDADE"/>
                    </form>
                    <a href="tabsTemplate.php"><button class="Descartar">CANCELAR</button></a>';
              unset($_REQUEST['changeBtn']);
              $stmt->close();
            }
          }

          //formulario para inserir a avaliaçao
          if(isset($_REQUEST['inserirAvaCli'])||isset($_REQUEST['inserirAvaPro']))
          {
            
              echo 'Inserir a avaliação para: ' . $_REQUEST['nomeP'];
              if(isset($_REQUEST['inserirAvaCli']))
              {
                echo '<form action="?inserirAvaCliBD" method="post">
                <input type="hidden" name="servicoID" value="'. $_REQUEST['inserirAvaCli'] .'"/>';
              }
              else
              {
                echo '<form action="?inserirAvaProBD" method="post">
                <input type="hidden" name="servicoID" value="'. $_REQUEST['inserirAvaPro'] .'"/>';
              }
              unset($_REQUEST['inserirAvaCli']);
              unset($_REQUEST['inserirAvaPro']);
                    echo'<input type="number" min="1" max="5" placeholder="Insira um valor de 1 a 5" name="avaValue" required/>
                    <input type="submit" value="AVALIAR"/>
                    </form>
                    <a href="tabsTemplate.php"><button class="Descartar">CANCELAR</button></a>';
          
            
          }
                    
          if(isset($personType)&&$personType=='funcionario')
          {
             
            echo ' <div id="Publicidade" class="tabcontent">
            <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
              <tr>
                <thead>
                  <th><b>Direcionada a: </b></th>
                  <th><b> Nome </b></th>
                  <th><b> URL </b></th>
                  <th><b> Editar </b></th>
                </thread>
              </tr>
    
              <tbody>
              <!-- ir buscar a base de dados informações da publicidade para preencher a tabela -->';
              if($stmtArea = $con->prepare("SELECT id,nome FROM area"))
              {
                
                $stmtArea->execute();
                //guarda os resultados
                $stmtArea->store_result();
                $area_num_rows= $stmtArea->num_rows;
                //so escreve a tabela se houver alguma area adicionada
                if($area_num_rows>0)
                {
                  for($i=0;$i<$area_num_rows;$i++)
                  {
                    $stmtArea->bind_result($areaID, $areaName);
                    $stmtArea->fetch();

                    if($stmtPub = $con->prepare("SELECT id,name,url FROM publicidade WHERE area_id=?"))
                    {
                      $stmtPub->bind_param('i',$areaID);
                      $stmtPub->execute();
                      $stmtPub->store_result();
                      $pub_num_rows = $stmtPub->num_rows;
                      //verifica se existe publicidade para a area escolhida
                      if($pub_num_rows>0)
                      {
                        echo '<tr>
                        <td rowspan="'.$pub_num_rows.'"> '. $areaName .' </td>';
                        for($j=0; $j<$pub_num_rows;$j++)
                        {
                          $stmtPub->bind_result($pubID, $pubName,$pubURL);
                          $stmtPub->fetch();
                          
                          echo '<td> '. $pubName .' </td>
                          <td> <a href="'. $pubURL .'"> '. $pubURL .' </a> </td>
                          <!-- botoes -->
                          <td>
                            <a href="tabsTemplate.php?changeBtn='.$pubID.'"><button class="Avaliar">Mudar URL</button></a>
                            <a href="tabsTemplate.php?deleteBtn='.$pubID.'"><button class="Descartar"> Eliminar </button></a>
                          </td> 
                          </tr>';
                        }
                      }
                    }
                  }
                }
              }
              echo '<a href="tabsTemplate.php?addBtn"><button class="Avaliar">ADICIONAR PUBLICIDADE</button></a>';
              echo '<tbody>
            </table>
          </div>';
              $stmtArea->close();
              $stmtPub->close();


              //reclamações
              echo ' <div id="Reclamacoes" class="tabcontent">
            <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
              <tr>
                <thead>
                  <th><b>Direcionada a: </b></th>
                  <th><b> Número Telefone </b></th>
                  <th><b> Reclamação </b></th>
                  <th><b> Editar </b></th>
                </thread>
              </tr>
    
              <tbody>
              <!-- ir buscar a base de dados informações da publicidade para preencher a tabela -->';
              if($stmt= $con->prepare("SELECT reclamacao.id, pessoa.nome, pessoa.nr_telefone, reclamacao.reclamacao FROM reclamacao,pessoa WHERE pessoa.id=reclamacao.pessoa_id"))
              {
                
                $stmt->execute();
                //guarda os resultados
                $stmt->store_result();
                $num_rows= $stmt->num_rows;
                //so escreve a tabela se houver alguma area adicionada
                if($num_rows>0)
                {
                  for($i=0;$i<$num_rows;$i++)
                  {
                    $stmt->bind_result($recID, $pessoaNome, $pessoaTl,$reclamacao);
                    $stmt->fetch();
                    
                    echo '<tr> <td> '. $pessoaNome .' </td>
                  <td> '. $pessoaTl .' </td>
                  <td> '. $reclamacao .' </td>
                  <!-- botoes -->
                  <td>
                  <a href="tabsTemplate.php?deleteRec='.$recID.'"><button class="Avaliar">Resolver</button></a>';
                   
                  }
                }
              }
              $stmt->close();
              echo '<tbody>
            </table>
          </div>';

          //pedidos de execucao
          echo ' <div id="Pedidos_de_execucao" class="tabcontent">
          <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
            <tr>
              <thead>
                <th><b> Cliente: </b></th>
                <th><b> Morada </b></th>
                <th><b> Descrição </b></th>
                <th><b> Data Limite </b></th>
                <th><b> Editar </b></th>
              </thread>
            </tr>
  
            <tbody>
            <!-- ir buscar a base de dados informações da publicidade para preencher a tabela -->';
            if($stmt= $con->prepare("SELECT execucao.id, pessoa.nome, execucao.morada, execucao.descricao,execucao.dataLimite FROM pessoa, execucao WHERE pessoa.id=execucao.pessoa_id"))
            {
              
              $stmt->execute();
              //guarda os resultados
              $stmt->store_result();
              $num_rows= $stmt->num_rows;
              //so escreve a tabela se houver alguma area adicionada
              if($num_rows>0)
              {
                for($i=0;$i<$num_rows;$i++)
                {
                  $stmt->bind_result($execID, $pessoaNome, $pessoaMorada,$execDesc,$execDataL);
                  $stmt->fetch();
                  
                  echo '<tr> <td> '. $pessoaNome .' </td>
                <td> '. $pessoaMorada .' </td>
                <td> '. $execDesc .' </td>
                <td> '. $execDataL .' </td>
                <!-- botoes -->
                <td>
                <a href="resolveExecucao.php?execId='.$execID.'"><button class="Avaliar">Executar Pedido </button></a>';
                 
                }
              }
            }
            $stmt->close();
            echo '<tbody>
          </table>
        </div>';

          }
          else if(isset($_SESSION['userData']))//se for login do facebook
          {
                //historico
          echo ' <div id="Historico" class="tabcontent">
          <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
            <tr>
              <thead>
                <th><b> Data inicio </b></th>
                <th><b> Data fim </b></th>
                <th><b> Area </b></th>
                <th><b> Custo </b></th>
              </thread>
            </tr>
  
            <tbody>
            <!-- ir buscar a base de dados informações da publicidade para preencher a tabela -->';
            if($stmt= $con->prepare("SELECT proposta.data_inicio,proposta.data_fim,area.nome as area,servico.custo_total FROM proposta,area,servico,pedido WHERE pedido.id=proposta.pedido_id AND pedido.id=servico.pedido_id AND pedido.area_id=area.id "))
            {
              
              $stmt->execute();
              //guarda os resultados
              $stmt->store_result();
              $num_rows= $stmt->num_rows;
              //so escreve a tabela se houver alguma area adicionada
              if($num_rows>0)
              {
                for($i=0;$i<$num_rows;$i++)
                {
                  $stmt->bind_result($dataInicio, $dataFim, $area, $custoTotal);
                  $stmt->fetch();
                  
                  echo '<tr> <td> '. $dataInicio .' </td>
                <td> '. $dataFim .' </td>
                <td> '. $area .' </td>
                <td> '. $custoTotal .' </td>';
              
                 
                }
              }
            }
            $stmt->close();
            echo '<tbody>
          </table>
        </div>';
          }
          else//se for do tipo empresa, cliente ou profissional mostra a tab da avaliação, pedidos e os teus serviços
          {
            
            $queryPed = "SELECT pedido.id, pessoa.nome, pedido.morada,pedido.dataLimite FROM pessoa,pedido WHERE pedido.aceita_proposta=0 AND pedido.profissional_pessoa_id=";
            $querySer = "SELECT servico.id, pessoa.nome, pedido.morada,proposta.data_inicio FROM pessoa,pedido,servico,proposta WHERE servico.inseriu_PDF=0 AND pedido.id=proposta.pedido_id AND pedido.aceita_proposta=1 AND pedido.profissional_pessoa_id=";
            $queryAva = "SELECT servico.id, pessoa.nome, pedido.descricao, servico.avaliacao_pro,servico.avaliacao_cli, servico.inseriu_PDF FROM pessoa,pedido,servico WHERE pedido.profissional_pessoa_id=";
            $queryVerProp = "SELECT DISTINCT proposta.id FROM proposta,pedido,pessoa WHERE proposta.pedido_id=pedido.id AND proposta.pedido_id=?";
            if($personType=='empresa'||$personType=='cliente')
            {
              $queryPedPub = "SELECT pedido.id, pedido.morada,pedido.dataLimite FROM pessoa,pedido WHERE pedido.aceita_proposta=0 AND pedido.profissional_pessoa_id=0 AND pessoa.id=pedido.pessoa_id AND pedido.pessoa_id=?";
              $tabName = 'Profissional';
              $tabConf = 'Estado';
              $queryPed.="pessoa.id AND pedido.pessoa_id=?";
              $querySer.="pessoa.id AND pedido.pessoa_id=? AND pedido.id=servico.pedido_id ";
              $queryAva.="pessoa.id AND pedido.pessoa_id=? AND pedido.id=servico.pedido_id AND servico.inseriu_PDF!=0";
              $queryVerProp.=" AND pedido.pessoa_id=pessoa.id AND pessoa.id=?";
            }
            else
            {
              $tabName = 'Cliente';
              $tabConf = 'Confirmar/Descartar';
              $queryPedPro="SELECT pedido.id,pessoa.nome, pedido.morada,pedido.dataLimite FROM pessoa,pedido,profissional WHERE pedido.aceita_proposta=0 AND pedido.profissional_pessoa_id=0 AND pessoa.id=pedido.pessoa_id AND pedido.area_id=profissional.area_id AND profissional.pessoa_id=?";
              $queryPed.="? AND pedido.pessoa_id=pessoa.id";
              $querySer.="? AND pedido.pessoa_id=pessoa.id AND pedido.id=servico.pedido_id";
              $queryAva.="? AND pedido.pessoa_id=pessoa.id AND pedido.id=servico.pedido_id AND servico.inseriu_PDF!=0";
              $queryVerProp.=" AND proposta.profissional_pessoa_id=?";
            }
            
          if($personType=='cliente'||$personType=='empresa')
          {
           echo '<div id="Pedidos" class="tabcontent">';           
          }
          else
          {
            echo '<div id="Pedidos_diretos" class="tabcontent">'; 
          }
          //pedidos privados para profissionais e para clientes
          echo '<table border="1" cellpadding="2" cellspacing="2" class="fl-table">
              <tr>
                <thead>
                  <th><b> '. $tabName .' </b></th>
                  <th><b> Morada </b></th>
                  <th><b> Data Limite </b></th>
                  <th><b> '. $tabConf .' </b></th>
                </thread>
              </tr>
              <tbody>
              <!-- ir buscar a base de dados informações do serviço para preencher a tabela -->';
              
              //preparar a expressao previne "SQL injections"
              if($stmt = $con->prepare($queryPed))
              {
                //dar bind dos parametros a expressao
                $stmt->bind_param('i', $id);
                $stmt->execute();
             
                $stmt->store_result();
                $stmt_num_rows=$stmt->num_rows();
              
                for($i=0;$i<$stmt_num_rows;$i++)
                {
                  $stmt->bind_result($idPedido, $nomePessoa, $moradaPed, $dataLPed);
                  $stmt->fetch();
                 
                  echo '<tr> <td> '. $nomePessoa .' </td>
                  <td> '. $moradaPed .' </td>
                  <td> '. $dataLPed .' </td>
                  <!-- botoes -->
                  <td>';
                  
                  if($stmtProp = $con->prepare($queryVerProp))
                    {
                      //dar bind dos parametros a expressao
                      $stmtProp->bind_param('ii',$idPedido ,$id);
                      $stmtProp->execute();
                  
                      $stmtProp->store_result();
                      $stmtProp_num_rows=$stmtProp->num_rows();
                    }

                  if($personType=='empresa'||$personType=='cliente')
                  {
                    
                      if($stmtProp_num_rows>0)
                      {
                        $stmtProp->bind_result($idProp);
                        $stmtProp->fetch();
                        echo '<a href="verPedido.php?idProp='.$idProp.'"><button class="Aceitar"> Ver Proposta </button></a>';
                      }
                      else
                      {
                        echo 'À espera de resposta ';
                      }
                      echo '<a href="tabsTemplate.php?deletePed='.$idPedido.'"><button class="Descartar"> Eliminar </button></a>';
                  }
                   else
                  {
                    if($stmtProp_num_rows>0)
                    {
                      $stmtProp->bind_result($idProp);
                      $stmtProp->fetch();
                      echo 'À espera de resposta ';
                    }
                    else
                    {
                      echo '<a href="proposta.php?idPed='.$idPedido.'"><button class="Aceitar"> Fazer Proposta </button></a>';
                    }
                    echo '<a href="tabsTemplate.php?deletePed='.$idPedido.'"><button class="Descartar"> Eliminar </button></a>';
                  }
                  echo '</td>
                  </tr>';
                }
              }
              //preparar a expressao previne "SQL injections"
              if(isset($queryPedPub)&&$stmt = $con->prepare($queryPedPub))
              {
                //dar bind dos parametros a expressao
                $stmt->bind_param('i', $id);
                $stmt->execute();
             
                $stmt->store_result();
                $stmt_num_rows=$stmt->num_rows();
              
                for($i=0;$i<$stmt_num_rows;$i++)
                {
                  $stmt->bind_result($idPedido, $moradaPed, $dataLPed);
                  $stmt->fetch();
                  
                  echo '<tr> <td> Á espera de profissional </td>
                  <td> '. $moradaPed .' </td>
                  <td> '. $dataLPed .' </td>
                  <!-- botoes -->
                  <td>';

                  if($stmtProp = $con->prepare($queryVerProp))
                  {
                    
                    //dar bind dos parametros a expressao
                    $stmtProp->bind_param('ii',$idPedido ,$id);
                    $stmtProp->execute();
                
                    $stmtProp->store_result();
                    $stmtProp_num_rows=$stmtProp->num_rows();
                  }
                  
                  if($personType=='empresa'||$personType=='cliente')
                  {
                    
                    if($stmtProp_num_rows>0)
                    {
                      $stmtProp->bind_result($idProp);
                      $stmtProp->fetch();
                      echo '<a href="verPedido.php?idProp='.$idProp.'"><button class="Aceitar"> Ver Proposta </button></a>';
                    }
                    else
                    {
                      echo 'À espera de resposta ';
                    }
                    echo '<a href="tabsTemplate.php?deletePed='.$idPedido.'"><button class="Descartar"> Eliminar </button></a>';
                  }
                  else
                  {
                    if($stmtProp_num_rows>0)
                    {
                      $stmtProp->bind_result($idProp);
                      $stmtProp->fetch();
                      echo 'À espera de resposta ';
                    }
                    else
                    {
                      echo '<a href="proposta.php?idPed='.$idPedido.'"><button class="Aceitar"> Fazer Proposta </button></a>';
                    }
                    echo '<a href="tabsTemplate.php?deletePed='.$idPedido.'"><button class="Descartar"> Eliminar </button></a>';
                  }
                  echo '</td>
                  </tr>';
                }
              }             
              echo '<tbody>
              </table>
            </div>';
      
            //pedidos publicos para os profissionais e os clientes
              echo '<div id="Pedidos_publicos" class="tabcontent">
              <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
                <tr>
                  <thead>
                    <th><b> '. $tabName .' </b></th>
                    <th><b> Morada </b></th>
                    <th><b> Data Limite </b></th>
                    <th><b> '. $tabConf .' </b></th>
                  </thread>
                </tr>
                <tbody>
                <!-- ir buscar a base de dados informações do serviço para preencher a tabela -->';
                
                //preparar a expressao previne "SQL injections"
                if(isset($queryPedPro)&&$stmt = $con->prepare($queryPedPro))
                {
                  //dar bind dos parametros a expressao
                  $stmt->bind_param('i', $id);
                  $stmt->execute();
               
                  $stmt->store_result();
                  $stmt_num_rows=$stmt->num_rows();

                  
                
                  for($i=0;$i<$stmt_num_rows;$i++)
                  {
                    $stmt->bind_result($idPedido, $nomePessoa, $moradaPed, $dataLPed);
                    $stmt->fetch();

                    if($stmtProp = $con->prepare($queryVerProp))
                    {
                      //dar bind dos parametros a expressao
                      $stmtProp->bind_param('ii',$idPedido ,$id);
                      $stmtProp->execute();
                  
                      $stmtProp->store_result();
                      $stmtProp_num_rows=$stmtProp->num_rows();
                    }
                    
                    echo '<tr> <td> '. $nomePessoa .' </td>
                    <td> '. $moradaPed .' </td>
                    <td> '. $dataLPed .' </td>
                    <!-- botoes -->
                    <td>';
                    
                    if($personType=='empresa'||$personType=='cliente')
                    {
                      if($stmtProp_num_rows>0)
                      {
                        $stmtProp->bind_result($idProp);
                        $stmtProp->fetch();
                        echo '<a href="verPedido.php?idProp='.$idProp.'"><button class="Aceitar"> Ver Proposta </button></a>';
                      }
                      else
                      {
                        echo 'À espera de resposta ';
                      echo '<a href="tabsTemplate.php?deletePed='.$idPedido.'"><button class="Descartar"> Eliminar </button></a>';
                      }
                    }
                    else
                    {
                      if($stmtProp_num_rows>0)
                      {
                        $stmtProp->bind_result($idProp);
                        $stmtProp->fetch();
                        echo 'À espera de resposta ';
                      }
                      else
                      {
                        echo '<a href="proposta.php?idPed='.$idPedido.'"><button class="Aceitar"> Fazer proposta </button></a>';                      
                      }
                      
                    }
                    
                    echo '</td>
                    </tr>';
                  }
                }
                echo '<tbody>
                </table>
              </div>';
        
            
          echo ' <div id="Servicos" class="tabcontent">
            <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
              <tr>
                <thead>
                  <th><b> '. $tabName .' </b></th>
                  <th><b> Morada </b></th>
                  <th><b> Data Início </b></th>
                  <th><b> Confirmar/Descartar </b></th>
                </thread>
              </tr>
    
              <tbody>
              <!-- ir buscar a base de dados informações do serviço para preencher a tabela -->';
             
             //preparar a expressao previne "SQL injections"
             if($stmt = $con->prepare($querySer))
             {
               //dar bind dos parametros a expressao
               $stmt->bind_param('i', $id);
               $stmt->execute();
              
               $stmt->store_result();
               $stmt_num_rows=$stmt->num_rows();
              
               for($i=0;$i<$stmt_num_rows;$i++)
               {
                 $stmt->bind_result($idServico, $nomePessoa, $moradaPed, $dataInicio);
                 $stmt->fetch();

              echo '<tr>
                <td> '. $nomePessoa .' </td>
                <td> '. $moradaPed .'</td>
                <td> '. $dataInicio .' </td>
                <!-- botoes -->
                <td>';
                if($personType=='profissional')
                {
                echo '<a href="finalsrvico.php?idSer='.$idServico.'"><button class="Fim"> Finalizar Serviço </button></a>';
                }
                else
                {
                  echo 'À espera de finalização';
                }
               echo '</td>
              </tr>';
               }
              }  
            echo '<tbody>
            </table>
            </div>

           <div id="Avaliacoes" class="tabcontent">
            <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
            <tr>
            <thead>
              <th><b> '.$tabName.' </b></th>
              <th><b> Descrição </b></th>
              <th><b> Avaliações </b></th>
            </thead>
            </tr>

            <tbody>
            <!-- ir buscar a base de dados informações do serviço para preencher a tabela -->';
            
            
            //preparar a expressao previne "SQL injections"
            if($stmt = $con->prepare($queryAva))
            {
              //dar bind dos parametros a expressao
              $stmt->bind_param('i', $id);
              $stmt->execute();
             
              $stmt->store_result();
              $stmt_num_rows=$stmt->num_rows();
            
              for($i=0;$i<$stmt_num_rows;$i++)
              {
                $stmt->bind_result($idServico, $nomePessoa, $descPed, $avaPro, $avaCli, $inseriuPDF);
                $stmt->fetch();
                
                echo '<tr>
                  <td> '. $nomePessoa .' </td>
                  <td> '. $descPed .' </td>';

                if($personType=='empresa'||$personType=='cliente')
                {
                  if($avaPro!=0)
                  {
                    echo '<td> '. $avaPro .' </td>
                    <!-- botoes -->';
                  }
                  else
                  {
                    echo '<td>
                    <a href="tabsTemplate.php?inserirAvaPro='.$idServico.'&nomeP='. $nomePessoa .'"><button class="Avaliar"> Avaliar </button></a>
                    </td>';
                  }
                }
                else
                {
                  if($avaCli!=0)
                  {
                    echo '<td> '. $avaCli .' </td>
                    <!-- botoes -->';
                  }
                  else
                  {
                    echo '<td>
                    <a href="tabsTemplate.php?inserirAvaCli='.$idServico.'&nomeP='. $nomePessoa .'"><button class="Avaliar"> Avaliar </button></a>
                    </td>';
                  }
                }
                  
                  
                
                echo '</tr>';
              }
            }
            echo '<tbody>
            </table>
            </div>';
          }
          if(isset($_SESSION['id']))
         {
            publicidadeFuncionario($_SESSION['id']);
         }
          if(isset($stmtProp))
          {
            $stmtProp->close();
          }
          ?>
    
      <script>
      function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
      }
      </script>
    </body>
  </head>
</html>
