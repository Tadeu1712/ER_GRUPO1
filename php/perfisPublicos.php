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
           <?php 
           if(isset($_SESSION['name']))
           {
           echo '<div class="dropdown">
          <li onclick="loginDropDown()" class="dropbtn">';
          
            
                echo $_SESSION['name'];
           
           echo' </li>
          <div id="dropDownAccount" class="dropdown-content">
              <a href="?logout">LOGOUT</a>

        </ul>
      </div>';
           }
           else
           {
            echo '<li class=""> <a href="login.php">LOGIN</a> </li></ul>
            </div>';
           }
?>
      <br><br><br><br><br><br><br><br><br>

      <div class="tab">
      <?php
       
       echo '<button class="tablinks" onclick="openCity(event, '. "'Clientes'" .')">Clientes</button>';
          echo '<button class="tablinks" onclick="openCity(event, '. "'Empresas'" .')">Empresas</button>';
          echo '<button class="tablinks" onclick="openCity(event, '. "'Profissionais'" .')">Profissionais</button>
          </div>';

        echo' <div id="Clientes" class="tabcontent">
          <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
          <tr>
          <thead>
            <th><b> Nome </b></th>
            <th><b> Avaliação </b></th>
          </thead>
          </tr>

          <tbody>
          <!-- ir buscar a base de dados informações do serviço para preencher a tabela -->';
        
   
        //preparar a expressao previne "SQL injections"
        if($stmt = $con->prepare("SELECT nome,avaliacao FROM pessoa WHERE tipo='cliente'"))
        {
        //dar bind dos parametros a expressao
        $stmt->execute();
        
        $stmt->store_result();
        $stmt_num_rows=$stmt->num_rows();
            
            for($i=0;$i<$stmt_num_rows;$i++)
            {
                $stmt->bind_result($nomePessoa, $avaliacao);
                $stmt->fetch();
                
                echo '<tr>
                <td> '. $nomePessoa .' </td>
                <td> '. $avaliacao .' </td>';

            }
        }
        echo '<tbody>
            </table>
            </div>';

            echo' <div id="Empresas" class="tabcontent">
            <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
            <tr>
            <thead>
              <th><b> Nome </b></th>
              <th><b> Avaliação </b></th>
            </thead>
            </tr>
  
            <tbody>
            <!-- ir buscar a base de dados informações do serviço para preencher a tabela -->';
          
     
          //preparar a expressao previne "SQL injections"
          if($stmt = $con->prepare("SELECT nome,avaliacao FROM pessoa WHERE tipo='empresa'"))
          {
          //dar bind dos parametros a expressao
          $stmt->execute();
          
          $stmt->store_result();
          $stmt_num_rows=$stmt->num_rows();
              
              for($i=0;$i<$stmt_num_rows;$i++)
              {
                  $stmt->bind_result($nomePessoa, $avaliacao);
                  $stmt->fetch();
                  
                  echo '<tr>
                  <td> '. $nomePessoa .' </td>
                  <td> '. $avaliacao .' </td>';
  
              }
          }
          echo '<tbody>
              </table>
              </div>';

              echo' <div id="Profissionais" class="tabcontent">
              <table border="1" cellpadding="2" cellspacing="2" class="fl-table">
              <tr>
              <thead>
                <th><b> Nome </b></th>
                <th><b> Avaliação </b></th>
              </thead>
              </tr>
    
              <tbody>
              <!-- ir buscar a base de dados informações do serviço para preencher a tabela -->';
            
       
            //preparar a expressao previne "SQL injections"
            if($stmt = $con->prepare("SELECT nome,avaliacao FROM pessoa WHERE tipo='profissional'"))
            {
            //dar bind dos parametros a expressao
            $stmt->execute();
            
            $stmt->store_result();
            $stmt_num_rows=$stmt->num_rows();
                
                for($i=0;$i<$stmt_num_rows;$i++)
                {
                    $stmt->bind_result($nomePessoa, $avaliacao);
                    $stmt->fetch();
                    
                    echo '<tr>
                    <td> '. $nomePessoa .' </td>
                    <td> '. $avaliacao .' </td>';
    
                }
            }
            echo '<tbody>
                </table>
                </div>';
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