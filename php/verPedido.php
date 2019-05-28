    <?php
    if (!session_id()) {
        session_start();
    }
        require_once('common.php');

        require_once('socialConfig.php');

        $con = connection();

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
    <section>
            <?php

                if($stmtProp = $con->prepare("SELECT proposta.custo_total, proposta.homens_hora,proposta.data_inicio,proposta.data_fim,proposta.pedido_id FROM proposta WHERE proposta.id=?"))
                {

                    $idProposta = $_REQUEST['idProp'];
                    //dar bind dos parametros a expressao
                    $stmtProp->bind_param('i',$idProposta);
                    $stmtProp->execute();

                    $stmtProp->store_result();
                    $stmtProp_num_rows=$stmtProp->num_rows();
                    
                    if($stmtProp_num_rows>0)
                    {
                        $stmtProp->bind_result($custo,$homensH,$dataI,$dataF,$idPed);
                        $stmtProp->fetch();

                        echo "<div class='showData' style='font-size:20px; margin-bottom:10px;'> Dados Inseridos:</div>";
                        echo "<div class='showData'> Data de inicio ".$dataI."</div>";
                        echo "<div class='showData'> Data de Fim: ".$dataF."</div>";
                        echo "<div class='showData'> Homens horas: ".$homensH."</div>";
                        echo "<div class='showData'> Custo total: ".$custo."</div>";

                        echo '<a href="tabsTemplate.php?aidProp='.$idProposta.'&idPed='.$idPed.'"><button class="Aceitar"> Aceitar </button></a>
                        <a href="tabsTemplate.php?eidProp='.$idProposta.'&idPed='.$idPed.'"><button class="Descartar"> Cancelar </button></a>';
                    }
                }
            ?>
            
        </section>   
    </body>
    </html>
