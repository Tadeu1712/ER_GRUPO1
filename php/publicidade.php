<!DOCTYPE html>
<?php
	session_start();
    require_once('common.php');

	if (!isset($_SESSION["name"])) {
		die("ERRO");
	}

$veryForType = doQuery("SELECT pessoa.id, pessoa.nome FROM pessoa, funcionarioLetMeFix 
	WHERE nome='{$_SESSION["name"]}'
	AND pessoa.id=funcionarioLetMeFix.pessoa_id");

$veryForTypever = mysqli_fetch_assoc($veryForType);

if ($_SESSION["name"] == $veryForTypever["nome"]) {
		?>

		<html lang="pt">
		<head>
			<meta charset="utf-8">publicidade.php
			<title>publicidade</title>
		<body>

		<form method="post">
			<div>
				<select name="area"  class="selectCatProf">
					<option value='0'>Escolher a Área</option>
					<option value="canalizador">Canalizador</option>
					<option value="carpinteiro">Carpinteiro</option>
					<option value="eletricista">Eletricista</option>
					<option value="pedreiro">Pedreiro</option>
				</select>
			</div>
		
			<div>
			<br>
				<input type="text" name="url">
			</div>
			<input type="hidden" name="estado" value="inserir">
					<input type="submit" value="Guardar URL"/>
		</form>
		<?php
		if ($_POST["estado"] == "inserir") {
			doQuery("INSERT INTO publicidade (`id`, `name`, `url`) VALUES (null, '{$_POST["url"]}', '{$_POST["url"]}')");
		}
			if (isset($_POST["area"])) {
					
				if ($_POST["area"] == "canalizador") {
					$resultProfissional = mysqli_fetch_assoc(doQuery("SELECT profissional.profissional_id
									   FROM profissional
									   WHERE profissional.area='{$_POST["area"]}'"));

					$arrayAI = $resultProfissional["profissional_id"];
					$arraySemP = str_replace("'", " ", $arrayAI);//substitui as peliculas por nada ,
					$arrayFim = explode(",",$arraySemP);//para separar num array neste caso o arraySemP, as strings que tem virgula pondo numa posiçao o que ta antes e depois da virgula
					$num = count($arrayFim);//vai contar os elementps de um array neste caso o arrayFim

					$resultPublicidade = mysqli_fetch_assoc(doQuery("SELECT publicidade.id
												  FROM publicidade 
												  WHERE publicidade.url='{$_POST["url"]}'"));

					for ($i=0; $i < $num; $i++) {
						doQuery("INSERT INTO `publicidade_has_profissional` (`publicidade_id`,`profissional_profissional_id`) 
						VALUES ('{$resultPublicidade["id"]}', '{$arrayAI[$i]}')");
					}
					
				} else if ($_POST["area"] == "carpinteiro") {
					$resultProfissional = mysqli_fetch_assoc(doQuery("SELECT profissional.profissional_id
					FROM profissional
					WHERE profissional.area='{$_POST["area"]}'"));

					$arrayAI = $resultProfissional["profissional_id"];
					$arraySemP = str_replace("'", " ", $arrayAI);//substitui as peliculas por nada ,
					$arrayFim = explode(",",$arraySemP);//para separar num array neste caso o arraySemP, as strings que tem virgula pondo numa posiçao o que ta antes e depois da virgula
					$num = count($arrayFim);//vai contar os elementps de um array neste caso o arrayFim

					$resultPublicidade = mysqli_fetch_assoc(doQuery("SELECT publicidade.id
												FROM publicidade 
												WHERE publicidade.url='{$_POST["url"]}'"));

					for ($i=0; $i < $num; $i++) {
						doQuery("INSERT INTO `publicidade_has_profissional` (`publicidade_id`,`profissional_profissional_id`) 
						VALUES ('{$resultPublicidade["id"]}', '{$arrayAI[$i]}')");
					}

				} else if ($_POST["area"] == "eletricista") {
					$resultProfissional = mysqli_fetch_assoc(doQuery("SELECT profissional.profissional_id
					FROM profissional
					WHERE profissional.area='{$_POST["area"]}'"));

					$arrayAI = $resultProfissional["profissional_id"];
					$arraySemP = str_replace("'", " ", $arrayAI);//substitui as peliculas por nada ,
					$arrayFim = explode(",",$arraySemP);//para separar num array neste caso o arraySemP, as strings que tem virgula pondo numa posiçao o que ta antes e depois da virgula
					$num = count($arrayFim);//vai contar os elementps de um array neste caso o arrayFim

					$resultPublicidade = mysqli_fetch_assoc(doQuery("SELECT publicidade.id
												FROM publicidade 
												WHERE publicidade.url='{$_POST["url"]}'"));

					for ($i=0; $i < $num; $i++) {
						doQuery("INSERT INTO `publicidade_has_profissional` (`publicidade_id`,`profissional_profissional_id`) 
						VALUES ('{$resultPublicidade["id"]}', '{$arrayAI[$i]}')");
					}
				} else if ($_POST["area"] == "pedreiro") {	
					$resultProfissional = mysqli_fetch_assoc(doQuery("SELECT profissional.profissional_id
					FROM profissional
					WHERE profissional.area='{$_POST["area"]}'"));

					$arrayAI = $resultProfissional["profissional_id"];
					$arraySemP = str_replace("'", " ", $arrayAI);//substitui as peliculas por nada ,
					$arrayFim = explode(",",$arraySemP);//para separar num array neste caso o arraySemP, as strings que tem virgula pondo numa posiçao o que ta antes e depois da virgula
					$num = count($arrayFim);//vai contar os elementps de um array neste caso o arrayFim

					$resultPublicidade = mysqli_fetch_assoc(doQuery("SELECT publicidade.id
												FROM publicidade 
												WHERE publicidade.url='{$_POST["url"]}'"));

					for ($i=0; $i < $num; $i++) {
						doQuery("INSERT INTO `publicidade_has_profissional` (`publicidade_id`,`profissional_profissional_id`) 
						VALUES ('{$resultPublicidade["id"]}', '{$arrayAI[$i]}')");
					}
				}
			}
					
		} else {
			?>
			<div>
				<h1>NAO TEM PERMISSÃO :( </h1>
			</div>
			<?php
			}
		
	?>
		</body>
</html>