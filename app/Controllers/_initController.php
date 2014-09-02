<?php

class _init extends Controller
{

	public function index_action($params = null)
	{

		$this -> _layout = 'login';

		$dados = '';

		if($_POST)
		{
			$host  = $_POST['host'];
			$user  = $_POST['user'];
			$senha = $_POST['senha'];
			$banco = $_POST['banco'];



			$link = mysql_connect($host, $user, $senha);
			if (!$link) {
			    die('Não foi possível conectar: ' . mysql_error());
			}

			mysql_query("CREATE DATABASE `{$banco}`");
			mysql_select_db($banco);

			mysql_query("CREATE TABLE IF NOT EXISTS `contato` (
			  `id_contato` int(11) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(45) DEFAULT NULL,
			  `email` varchar(45) DEFAULT NULL,
			  `mensagem` text,
			  `data` datetime DEFAULT NULL,
			  PRIMARY KEY (`id_contato`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15");


			mysql_query("CREATE TABLE IF NOT EXISTS `developer` (
			  `id_developer` int(11) NOT NULL AUTO_INCREMENT,
			  `nome` varchar(45) DEFAULT NULL,
			  `login` varchar(45) DEFAULT NULL,
			  `senha` varchar(128) DEFAULT NULL,
			  PRIMARY KEY (`id_developer`),
			  UNIQUE KEY `login_UNIQUE` (`login`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3");


			$ok = mysql_query("INSERT INTO `developer` (`id_developer`, `nome`, `login`, `senha`) VALUES
			(1, 'admin', 'admin', 'e77add3116f725410440e1b21259b1d3c9d2fccae0d51a43be4dd435cafabef2db63b615b2a9811200542b9c9f35fc14b733452c4c6a6d5c53879577db0a0f36')");

			$dados['status'] = $this -> retorno($ok);
		}

		$this->view('index', $dados);
	}


	private function retorno($ok)
	{
		

		if($ok)
		{
			return '

			  <h3>Banco de dados criado com sucesso!</h3>
			  Por segurança exclua o arquivo 
			  <strong><em>_initController.php</em></strong> da pastra /Controller e a pasta <strong><em>_Init</em></strong> dentro da pasta View

			';
		}
		else
		{
			return 'Erro ao criar banco de dados!';
		}


	}

}