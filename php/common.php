<?php

function doQuery ($query) {
	$DB_HOST = 'localhost';
	$DB_USER = 'root';
	$DB_PASSWORD = '' ;
	$DB_NAME = 'mydb';
	//ligacao a base de dados
	$link = mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME);
 
	//caso a ligaçao falhar
	if(!$link)
	{
	  die('Erro na ligacao: '.mysqli_connect_error());
	}
 
	//execucao da query
	   $result = mysqli_query($link,$query);
 
 //caso exista um erro de execucao
	if(!$result)
	{
	  die('Erro na execução da query: '.$query);
	}
 
	//fecha a ligacao da base de dados
	mysqli_close($link);
 
 //retirna o resultado
		return $result;
  }
 //Metodo responsavel por fazer a execução de um query e retornar o resultado
function connection()
{
	$DB_HOST = 'localhost';
	$DB_USER = 'root';
	$DB_PASSWORD = '' ;
	$DB_NAME = 'mydb';

	//faz a ligação a base de dados
	$link = mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME);
	
	if(!$link)
	{
		die("Erro ao efetuar ligação: ".mysqli_connect_error());
	}
	
	return $link;

}

function publicidadeFuncionario($idPro) {
	?>
		<style>
			
			.divPub {
			margin-left: 1300px;
			}
			
			ul {
			list-style-type: none;
			width: 500px;
			}
			
			h3 {
			font: bold 20px/1.5 Helvetica, Verdana, sans-serif;
			}
			
			li img {
			float: left;
			margin: 0 15px 0 0;
			}
			
			li p {
			font: 200 12px/1.5 Georgia, Times New Roman, serif;
			}
			
			li {
			padding: 10px;
			overflow: auto;
			}
			
			li:hover {
			background: #eee;
			cursor: pointer;
			}
		</style>
	<?php
					$userspecialization = doQuery("SELECT publicidade.name, publicidade.url, area.nome 
					FROM publicidade, area, pessoa, profissional
					WHERE pessoa.id=$idPro
					AND pessoa.id=profissional.pessoa_id
					AND profissional.area_id=area.id
					AND area.id=publicidade.area_id");
			
			$Nuserspecialization = mysqli_num_rows($userspecialization);
	
			for ($i=0; $i < $Nuserspecialization; $i++) {
			$userspecializationver = mysqli_fetch_assoc($userspecialization);		
				?>
				<body>
				<div class="divPub">
					<ul>
						<li><a href=<?php echo $userspecializationver['url']; ?>>
							<?php
							if ($userspecializationver["nome"] == 'canalizacao') {
								?>
									<img src="../img/canalizacao.jpg" />
								<?php
							} else if ($userspecializationver["nome"] == 'carpintaria') {
								?>
									<img src="../img/carpintaria.jpg"  />
								<?php
							} else if ($userspecializationver["nome"] == 'eletrecidade') {
								?>
									<img src="../img/eletrecidade.jpg" />
								<?php
							} else if ($userspecializationver["nome"] == 'construcao civil') {
								?>
									<img src="../img/construcao_civil.jpg"  />
								<?php
							}
							?>
							<h3><?php echo $userspecializationver["name"] ?></h3>
							</a></li>
					</ul>
				</div>
				
			</body>
				<?php
			}
}
?>
	
	
