<?php

/**
* 
*/
class AuthHelper
{

	private $_redir;
	private $_bd;
	private $_tableName;
	private $_userColumn		= 'login';
	private $_senhaColumn		= 'senha';
	private $_user;
	private $_senha;
	private $_controllerLogado 	= 'admin';
	private $_actionLogado 		= 'index';
	private $_controllerErro    = 'admin';
	private $_actionErro 		= 'login';


	//GETTER AND SETTER
	public function __set($atrib, $value){  $this->$atrib = $value; }
	public function __get($atrib){  return $this->$atrib; }
	
	



	function __construct() 
	{
		//CRIA O OBJETO QUE AUXILIA O REDIRECIONAMENTO
		$this->_redir = new RedirectHelper();

		//INICIO O MODELO DO BANCO
		$this -> _bd = new Model();
	}

		


	/*
	 * Método para fazer o login do usuário
	 */

	public function login()
	{

		//PASSO A TABELA QUE ESTÁ OS DADOS PARA LOGIN
		$this -> _bd->_tabela = $this->_tableName;
		
		//DEFINO A STRING DE COMPARAÇÃO
		$where = $this->_userColumn . " = '" . $this->_user . "' AND " . $this->_senhaColumn . " = '" . hash('sha512', $this->_senha) . "'";

		//FAZ A CONSULTA
		$sql = $this -> _bd -> readLine($where);

		//SE EXISTIR ELE LOGA E CRIA A SESSÃO
		if(count($sql) > 0 AND !empty($sql) )
		{
			$_SESSION['user'] = $sql[$this->_userColumn];
			$_SESSION['dados_usuario'] = $sql;
			$_SESSION['logado'] = 1;
			$_SESSION['hash'] = sha1(DATABASE_CONFIG::$default['banco']);

			return true;
		}
		else //SE NÃO EXISTIR REDIRECIONA PARA A PAGINA DE LOGIN NOVAMENTE
		{
			return false;
		}		
	}



	public function logout()
	{
		$_SESSION = array(); 			 
		// Destroi a Sessão
		session_destroy();
		// Modifica o ID da Sessão
		session_regenerate_id();
		// redireciona
		$this->_redir->goToControllerAction($this->_controllerErro, $this->_actionErro);
	}


	public function verificaLogin()
	{
		
		//exit();
		if(isset($_SESSION['user']) AND isset($_SESSION['dados_usuario']) AND $_SESSION['logado'] == 1 AND $_SESSION['hash'] == sha1(DATABASE_CONFIG::$default['banco']))
		{
			return $this -> permissaoArea();
		}
		else
		{
			session_destroy();
			return false;
		}
	}



	private function permissaoArea()
	{
		//DEFINO A STRING DE COMPARAÇÃO
		$where = $this->_userColumn . " = '" . $_SESSION['user'] . "' AND " . $this->_senhaColumn . " = '" . $_SESSION['dados_usuario']['senha'] . "'";
		//FAZ A CONSULTA
		//return "SELECT * FROM ´" . $this -> _tableName . "´ WHERE " . $where;
		$this -> _bd -> _tabela = $this -> _tableName;
		return $this -> _bd -> readLine($where);
	}
}