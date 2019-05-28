<?php
if (!session_id()) {
  session_start();
}
require_once("common.php");
require_once("socialConfig.php");

$redirectURL = "http://localhost/ER/php/fb_callback.php";
$permissions = ['email'];
$loginURL = $helper->getLoginURL($redirectURL,$permissions);

if(isset($_SESSION['accessToken']))
{
  header('Location: home.php');
}   

?>

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="utf-8">
    <link href="../css/style1.css" rel="stylesheet">
    <title>Login & Registration</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
  </head>

  <style media="screen">
  <!DOCTYPE html>
<html>
<head>
<style>

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

</style>
</body>
</html>
  <body>
    <head>
        <script src="../js/communication.js" type="text/javascript"></script>
    </head>
    <header>
      <div class="row">
        <div class="logo">
          <a href="welcome.php"> <img src="../img/logo.png"></a>
        </div>
        <ul class="main-nav">

        <li class=""> <a href="perfisPublicos.php">UTILIZADORES</a> </li>
          <li class=""> <a href="#">SOBRE</a> </li>
          <li class=""> <a onclick="return PopComunication('http:/\/localhost:3000/')" href="#">CONTACTO</a> </li>
          <li class=""> <a href="login.php">LOGIN</a> </li>
          <li class="fbLogin"> <a href=<?php echo $loginURL?>>LOGIN COM FACEBOOK</a> </li>

        </ul>
      </div>

      <div class="login-page">
        <div class="form">

          <form method="post" class="register-form">

            <select name="tipo" id="selectCat" onchange="showDiv(this)" class="appearance-select">
             <option value="0">Selecione</option>
             <option value="cliente">Cliente</option>
             <option value="funcionario">Funcionario</option>
             <option value="profissional">Profissional</option>
             <option value="empresa">empresa</option>
             
            </select>
            </form>
            
            <!-- mostra form para profissional-->
            <div id="formPro" class="hideForm">
            <form name="formReg" id="formReg" action="register.php" method="post" class="register-form">
              <input type="text" name="name" placeholder="name" id="name" required/>
              <input type="text" name="NIF" placeholder="NIF" id="NIF" required/>
              <input type="text" name="nr_telefone" placeholder="nr_telefone" id="nr_telefone" required/>
              <input type="text" name="morada" placeholder="morada" id="morada" required/>
              <input type="text" name="username" placeholder="username" id="username" required/>
              <input type="password" name="password" placeholder="Password" id="password" required/>
              <input type="password" name="conf_password" placeholder="Confirme Password" id="conf_password" required/>
              <input type="text" name="email" id="email" placeholder="Email"/>
              <input type="number" step="0.01" name="precoH" id="precoH" placeholder="Preço Hora"/> 
              <textarea placeholder="Equipamento" id="equipamento" name="equipamento"></textarea>     
              <select name="area" id="area" class="appearance-select">
              <option value="eletrecidade">Eletricidade</option>
             <option value="canalizacao">Canalização</option>
             <option value="construcao civil">Construção Civil</option>
             <option value="carpintaria">Carpintaria</option>
              </select>    
              <input type="hidden" value="profissional" name="tipo"/>
              <input type="submit" value="REGISTAR"/>
              </form>
            </div>

             <!-- mostra form para empresa-->
             <div id="formEmp" class="hideForm">
            <form name="formReg" id="formReg" action="register.php" method="post" class="register-form">
              <input type="text" name="name" placeholder="name" id="name" required/>
              <input type="text" name="NIF" placeholder="NIF" id="NIF" required/>
              <input type="text" name="nr_telefone" placeholder="nr_telefone" id="nr_telefone" required/>
              <input type="text" name="morada" placeholder="morada" id="morada" required/>
              <input type="text" name="username" placeholder="username" id="username" required/>
              <input type="password" name="password" placeholder="Password" id="password" required/>
              <input type="password" name="conf_password" placeholder="Confirme Password" id="conf_password" required/>
              <input type="text" name="email" id="email" placeholder="Email"/>
              <input type="text" name="ramoEmp" id="ramoEmp" placeholder="Ramo da empresa"/>  
              <input type="hidden" value="empresa" name="tipo"/>           
              <input type="submit" value="REGISTAR"/>
              </form>
            </div>

            <!-- mostra form para o cliente-->
           <div id="formCliente" class="hideForm">
            <form name="formReg" id="formReg" action="register.php" method="post" class="register-form">
              <input type="text" name="name" placeholder="name" id="name" required/>
              <input type="text" name="NIF" placeholder="NIF" id="NIF" required/>
              <input type="text" name="nr_telefone" placeholder="nr_telefone" id="nr_telefone" required/>
              <input type="text" name="morada" placeholder="morada" id="morada" required/>
              <input type="text" name="username" placeholder="username" id="username" required/>
              <input type="password" name="password" placeholder="Password" id="password" required/>
              <input type="password" name="conf_password" placeholder="Confirme Password" id="conf_password" required/>
              <input type="text" name="email" id="email" placeholder="Email"/>
              <input type="hidden" value="cliente" name="tipo"/>             
              <input type="submit" value="REGISTAR"/>
              </form>
            </div>

            <!-- mostra form para o funcionario-->
           <div id="formFuncionario" class="hideForm">
            <form name="formReg" id="formReg" action="register.php" method="post" class="register-form">
              <input type="text" name="name" placeholder="name" id="name" required/>
              <input type="text" name="NIF" placeholder="NIF" id="NIF" required/>
              <input type="text" name="nr_telefone" placeholder="nr_telefone" id="nr_telefone" required/>
              <input type="text" name="morada" placeholder="morada" id="morada" required/>
              <input type="text" name="username" placeholder="username" id="username" required/>
              <input type="password" name="password" placeholder="Password" id="password" required/>
              <input type="password" name="conf_password" placeholder="Confirme Password" id="conf_password" required/>
              <input type="text" name="email" id="email" placeholder="Email"/>
              <input type="hidden" value="funcionario" name="tipo"/>             
              <input type="submit" value="REGISTAR"/>
              </form>
            </div>
            <p class="message">Já Registado? <a href="#">Login</a></p>

     
          
          <form action="login_app.php" class="login-form" method="post">
            <input type="text"  id="username" name="username" placeholder="Username" required/>
            <input type="password" id="password" name="password" placeholder="Password" required/>
            <input type="submit" value="LOGIN"/>
            <p class="message">Não Registado? <a href="#">Registar</a> </p>
          </form>

        </div>
      </div>

      <script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>

      <script>
      //aparecer ou desaparecer formulario de registo
       function showDiv(element)
        {
            var tipo = document.getElementById('selectCat').value;
            
            if(tipo === 'funcionario' )
            {
              divId = 'formFuncionario';
            }
            else if(tipo === 'cliente')
            {
              divId = 'formCliente';
            }
            else if(tipo === 'empresa')
            {
              divId = 'formEmp';
            }
            else if(tipo === 'profissional')
            {
              divId = 'formPro';
            }
            document.getElementById(divId).style.display = element.value != 0 ? 'block' : 'none';
        }
      </script> 
      

      <script>//login -> registo; registo -> login
        $('.message a').click(function(){
          $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
        });
      </script>
    </header>
    <?php
    if(isset($_SESSION['loggedin']))
    {
      if($_SESSION['loggedin']=="err")
      {
          echo '<script> alert("Password ou Username incorretos") </script>';
          unset($_SESSION['loggedin']);
      }
      else if($_SESSION['loggedin'=='emp'])
      {
        echo '<script> alert("Preencha todos os campos") </script>';
        unset($_SESSION['loggedin']);
      }
    
    }

    if(isset($_SESSION['register']))
    {
      if($_SESSION['register']=="pw")
      {
          echo '<script> alert("Passwords não correspondem") </script>';
          unset($_SESSION['register']);
      }
      else if($_SESSION['register']=="email")
      {
          echo '<script> alert("Este email nao pode ser utilizado") </script>';
          unset($_SESSION['register']);
      }
      else if($_SESSION['register']=="user")
      {
          echo '<script> alert("Este username já esta a ser utilizado") </script>';
          unset($_SESSION['register']);
      }
      else if($_SESSION['register']=="succ")
      {
          echo '<script> alert("Registo efetuado com sucesso") </script>';
          unset($_SESSION['register']);
      }
      else if($_SESSION['register']=="emp")
      {
          echo '<script> alert("Preencha todos os campos") </script>';
          unset($_SESSION['register']);
      }
    
    }
    ?>
  </body>
</html>


