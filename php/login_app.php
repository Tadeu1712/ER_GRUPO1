<?php
if (!session_id()) {
    session_start();
}
require_once("common.php");
//require_once("home.php");
require_once("socialConfig.php");
 


$con = connection();

// verificar se os campos do login foram preenchidos
if (!isset($_REQUEST['username'], $_REQUEST['password']) ) 
{
	// os dados nao foram recebidos
	$_SESSION['loggedin']="emp";
	header('Location: login.php');
	
}

//preparar a expressao previne "SQL injections"
if($stmt = $con->prepare("SELECT id, password, tipo FROM pessoa WHERE username = ?"))
{
	//dar bind dos parametros a expressao
	$stmt->bind_param('s', $_REQUEST['username']);
	$stmt->execute();
	//guardar os resultados para ver se a conta existe na base de daods
	$stmt->store_result();

	//verificar se existe algum utilizador que corresponda as condições escolhidas
	if($stmt->num_rows>0)
	{
		$stmt->bind_result($id, $password, $tipo);
		$stmt->fetch();
		//conta existe, verificar a password
		if(password_verify($_REQUEST['password'],$password))
		{
			//criaçao de sessoes para lembrar que o utilizador tem sessao inciada
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $_REQUEST['username'];
			$_SESSION['id'] = $id;
			$_SESSION['tipo'] = $tipo;
			unset($_REQUEST['username']);
			unset($_REQUEST['password']);

			header('Location: home.php');
		}
		else
		{
			$_SESSION['loggedin']="err";
			header('Location: login.php');
			
		}
	}
	else
	{
		$_SESSION['loggedin']="err";
		header('Location: login.php');
		
	}
	$stmt->close();
	
}
?>

