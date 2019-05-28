<?php
   if (!session_id()) {
    session_start();
}
    require_once('common.php');

    require_once('socialConfig.php');

  if(isset($_REQUEST['logout']))
  {
    unset($_SESSION['accessToken']);
    unset($_SESSION['loggedin']);
		unset($_SESSION['name']);
    unset($_SESSION['id']);
    unset($_SESSION['userData']);
    unset($_SESSION['tipo']);
    header('Location: login.php');
    exit();
  }

   if(!isset($_SESSION['name']) && isset($_SESSION['userData']))
   {
     $_SESSION['name']=$_SESSION['userData']['first_name'] . ' ' . $_SESSION['userData']['last_name'];
   }

   if(isset($_SESSION['tipo'])&&$_SESSION['tipo']=='funcionario')
   {
    header('Location: tabsTemplate.php');
    exit();
  } 
 
?>
<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <link href="../css/style1.css" rel="stylesheet">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <style>


   .row{
    height: 150px;
   }

    /* Shared */
    .loginBtn {
    box-sizing: border-box;
    position: relative;
    /* width: 13em;  - apply for fixed size */
    margin: 0.2em;
    padding: 0 15px 0 46px;
    border: none;
    text-align: left;
    line-height: 34px;
    white-space: nowrap;
    border-radius: 0.2em;
    font-size: 16px;
    color: #FFF;
    }

    .loginBtn:before {
    content: "";
    box-sizing: border-box;
    position: absolute;
    top: 0;
    left: 0;
    width: 34px;
    height: 100%;
    }
    .loginBtn:focus {
    outline: none;
    }

    .loginBtn--facebook {
    background-color: #4C69BA;
    background-image: linear-gradient(#4C69BA, #3B55A0);
    /*font-family: "Helvetica neue", Helvetica Neue, Helvetica, Arial, sans-serif;*/
    text-shadow: 0 -1px 0 #354C8C;
    }
    .loginBtn--facebook:before {
    border-right: #364e92 1px solid;
    background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/14082/icon_facebook.png') 6px 6px no-repeat;
    }
    .loginBtn--facebook:hover,
    .loginBtn--facebook:focus {
    background-color: #5B7BD5;
    background-image: linear-gradient(#5B7BD5, #4864B1);
    }
    .loginBtn:active {
    box-shadow: inset 0 0 0 32px rgba(0,0,0,0.1);
    }

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

* {
  box-sizing: border-box;
}
.profile{
    
	margin: auto;
	margin-top: 150px;
	width:80%;
    height: 45%;
    background: rgba(0, 88, 92, 0.8);
    padding:15px;
}

/*.photo{
	border-radius:50%;
    background-color:white;
    height:100%;
    width:25%;
    float:left;
    /* onde esta url meter imagem a ficar no perfil 
    background-image: linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url(../img/defaultuser.png);
    background-size: cover;
    background-repeat: no-repeat;
    border:6px solid #0B5BAC;
}*/

.description{
    padding-top:5px;
    padding-left:10px;
	margin-left:30%;
	width: 67%;
    height:100%;
    background-color:rgba(204, 204, 204, 0.46);
}

.description:after{
  content: "";
  display: table;
  clear: both;
}

h1, h3{
    display: inline;
    margin: 5px;
    font-family: initial;
}

h1{
    color:#07323F;
}

h3{
    color:white;
}

.column {
  float: left;
  width: 50%;
  padding: 10px;
}
    </style>
</head>
  <body>
    <script src="../js/navbarConf.js"></script>
    <head>
        <script src="../js/communication.js" type="text/javascript"></script>
    </head>
    <header>
      <section>
      <div class="row">
        <div class="logo">
          <a href="welcome.php"> <img src="../img/logo.png"></a>
        </div>

        <ul class="main-nav">
        <li class=""> <a href="perfisPublicos.php">UTILIZADORES</a> </li>
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
         
        </div>
      </div>
      </section>
    </header>

  

<section class="profile">
  
  <div style="border-radius:50%;
    background-color:white;
    height:100%;
    width:25%;
    float:left;
    /* onde esta url meter imagem a ficar no perfil */
    background-image: url(<?php
     if(isset($_SESSION['userData']))
     {
       echo $_SESSION['userData']['picture']['url'];
     }
     else
     {
       echo '../img/defaultuser.png';
     }
     ?>);
    background-size: cover;
    background-repeat: no-repeat;
    border:6px solid #0B5BAC;">
	
  </div>
  <?php
    if(isset($_SESSION['tipo']))
   {
     $tipo=$_SESSION['tipo'];
     $id = $_SESSION['id'];
     if( $tipo== 'cliente' || $tipo == "empresa" ){
       
    
    $info = doQuery("SELECT nome, username, morada, tipo, email, nr_telefone, avaliacao FROM pessoa WHERE id = $id");
    $infos = mysqli_fetch_assoc($info);

    $empresa= doQuery("SELECT DISTINCT ramo_empresa FROM empresa, pessoa WHERE empresa.pessoa_id = $id");
    $empr = mysqli_fetch_assoc($empresa);
    $nEmpr = mysqli_num_rows($empresa);

  ?>
  <div class="description">
    <div class="column">
        <h1>Name:</h1><h3><?php echo $infos["nome"]; ?></h3>
        <br><br><br>
        <h1>Username:</h1><h3><?php echo $infos["username"]; ?></h3>
        <br><br><br>
        <h1>Morada:</h1><h3><?php echo $infos["morada"]; ?></h3>
        <br><br><br>
        <h1>Tipo:</h1><h3><?php echo $infos["tipo"]; ?></h3>
        
    </div>
    <div class="column">

        <?php if($nEmpr > 0){?>
            <h1>Ramo da Empresa:</h1><h3><?php echo $empr["ramo_empresa"]; ?></h3>
            <br><br>
        <?php } ?>

        <h1>Email:</h1><h3><?php echo $infos["email"]; ?></h3>
        <br><br>
        <h1>Nr. Telefone:</h1><h3><?php echo $infos["nr_telefone"]; ?></h3>
        <br><br>
        <h1>Avaliação:</h1><h3><?php echo $infos["avaliacao"]; ?> /5</h3>

    </div>

    </div>

</section>
<?php

} else if($tipo=='profissional'){
  $profissionalInfo = doQuery("SELECT DISTINCT pessoa.nome, pessoa.username, pessoa.email, pessoa.morada, 
  pessoa.tipo, pessoa.nr_telefone, pessoa.avaliacao, profissional.preco_hora, 
  profissional.equipamento FROM profissional, pessoa WHERE pessoa.id = $id 
  AND profissional.pessoa_id = pessoa.id ");

  $areaProf = doQuery("SELECT DISTINCT area.nome FROM profissional, pessoa, area WHERE 
  pessoa.id = $id AND profissional.pessoa_id = pessoa.id AND area.id=profissional.area_id ");
  
  $profInfo = mysqli_fetch_assoc($profissionalInfo);
  $area = mysqli_fetch_assoc($areaProf);

?>


<section style="margin: auto;
	margin-top: 75px;
	width:80%;
    height: 70%;
    background: rgba(0, 88, 92, 0.8);
    padding:15px;">
  <div class="photo">
	
  </div>
  
  <div class="description">
    <div class="column">
        <h1>Name:</h1><h3><?php echo $profInfo['nome']; ?></h3>
        <br><br><br>
        <h1>Username:</h1><h3><?php echo $profInfo['username']; ?></h3>
        <br><br><br>
        <h1>Morada:</h1><h3><?php echo $profInfo['morada']; ?></h3>
        <br><br><br>
        <h1>Tipo:</h1><h3><?php echo $profInfo['tipo']; ?></h3>
        <br><br><br>
        <h1>Área:</h1><h3><?php echo $area['nome']; ?></h3>
        
    </div>
    <div class="column">
        <h1>Email:</h1><h3><?php echo $profInfo['email']; ?></h3>
        <br><br>
        <h1>Nr. Telefone:</h1><h3><?php echo $profInfo['nr_telefone']; ?></h3>
        <br><br>
        <h1>Preço/Hora:</h1><h3><?php echo $profInfo['preco_hora']; ?> €/hora</h3>
        <br><br>
        <h1>Equipamento:</h1><h3><?php echo $profInfo['equipamento']; ?></h3>
        <br><br>
        <h1>Avaliação:</h1><h3><?php echo $profInfo['avaliacao']; ?> / 5</h3>

    </div>

    </div>

</section>
<?php
}

}else if(isset($_SESSION['userData']))
{
  echo '

    <section style="" class="profile">
      <div class="photo">
      
      </div>
      
      <div class="description">
        <div class="column">
            <h1>Name:</h1><h3>'. $_SESSION['userData']['first_name'] . ' '. $_SESSION['userData']['last_name'] .'</h3>
            <br><br><br>
            <h1>Email:</h1><h3>'. $_SESSION['userData']['email'] .'</h3>
            <br><br><br>
            
            
        </div>
        <div class="column">
        </div>
        </div>
    
    </section>';
}


?>

  </body>
</html>
