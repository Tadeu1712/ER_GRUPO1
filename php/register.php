<?php
require_once("common.php");
 
session_start();

$con = connection();

// verificar se os campos do login foram preenchidos
if(!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['conf_password']))
{
	// os dados nao foram recebidos
	$_SESSION['register'] = "emp";
	header('Location: login.php');

	
}

//preparar a expressao previne "SQL injections"
if($stmt = $con->prepare("SELECT id, password, email FROM pessoa WHERE username = ?"))
{
	//dar bind dos parametros a expressao
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	//guardar os resultados para ver se a conadminta existe na base de daods
	$stmt->store_result();

	//verificar se existe algum utilizador que corresponda as condições escolhidas, se nao existir cria a conta
	if($stmt->num_rows<=0 && $_POST['username'] != 'admin')
	{
		//verifica se é usado o email do admin ou se é usado um email com conta ja criada
		if($_POST['email'] != 'admin@admin.com')
		{
			//verifica se as passwords introduzidas correspondem
			if($_POST['password'] === $_POST['conf_password'])
			{
				//se nao houver um username igual e as passwords corresponderem
				if($stmt = $con->prepare("INSERT INTO `pessoa` (`id`, `nome`,`NIF`,`nr_telefone`,`morada`, `username`, `password`, `email`,`tipo`) VALUES (NULL,?,?,?,?,?,?,?,?)"))
				{
					
					$options = [
	   				'cost' => 12,
					];
					$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
					//dar bind dos parametros a expressao
					$stmt->bind_param('siisssss', $_POST['name'], $_POST['NIF'], $_POST['nr_telefone'], $_POST['morada'], $_POST['username'], $password, $_POST['email'],$_POST['tipo']);
					$stmt->execute();
					
					if($stmt = $con->prepare("SELECT id FROM pessoa WHERE username=? "))
					{
						$stmt->bind_param('s',$_POST['username']);
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($id);
						$stmt->fetch();
					}
					
					if($_REQUEST['tipo']=='profissional')
					{
						if($stmt = $con->prepare("SELECT id FROM area WHERE nome=? "))
						{
						$stmt->bind_param('s',$_POST['area']);
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($idArea);
						$stmt->fetch();
						}

						if($stmt = $con->prepare("INSERT INTO `profissional` (`pessoa_id`, `preco_hora`,`equipamento`,`area_id`) VALUES (?,?,?,?)"))
						{
							$stmt->bind_param('iisi',$id,$_POST['precoH'],$_POST['equipamento'],$idArea);
							$stmt->execute();
						}
					}
					if($_REQUEST['tipo']=='empresa')
					{
						if($stmt = $con->prepare("INSERT INTO `empresa` (`pessoa_id`, `ramo_empresa`) VALUES (?,?)"))
						{
							$stmt->bind_param('is',$id,$_POST['ramoEmp']);
							$stmt->execute();
						}
					}
	
						 
					$_SESSION['register'] = "succ";
					header('Location: login.php');					
				}
			}
			else
			{
				$_SESSION['register'] = "pw";
				header('Location: login.php');


			}

		}
		else
		{
			$_SESSION['register'] = "email";
			header('Location: login.php');
			$stmt->close();
		}

	

	}
	else
	{
		$_SESSION['register'] = "user";
		header('Location: login.php');
		
	}
	$stmt->close();
	
}

?>

