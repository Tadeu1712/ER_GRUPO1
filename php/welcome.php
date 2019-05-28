<?php
session_start();

if(isset($_SESSION['id']))
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
    <style>

    .LetMeFix{
      color: #FFCF60;
      text-align: center;
      font-family: "Roboto", sans-serif;
      font-size: 70px;
      text-shadow: 5px 3px #A3ACB9;
      letter-spacing: 7px;
    }

    .slogan{
      color: #FFCF60;
      text-align: center;
      font-family: "Roboto", sans-serif;
      font-size: 30px;
      text-shadow: 3px 1px #A3ACB9;
      letter-spacing: 7px;
    }

    </style>
</head>
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
        </ul>
      </div>
<br><br><br><br><br><br><br><br><br><br><br>

      <div>
        <h1 class="LetMeFix">LetMeFix</h1>
        <p class="slogan">We make it happen!</p>
      </div>

      <script>

            function myFunction() {
              document.getElementById("myDropdown").classList.toggle("show");
            }

            // Close the dropdown if the user clicks outside of it
            window.onclick = function(event) {
              if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                  var openDropdown = dropdowns[i];
                  if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                  }
                }
              }
            }
            </script>



    </header>
  </body>
</html>
