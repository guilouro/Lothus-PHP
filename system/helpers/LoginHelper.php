<?php

/**
* Classe para autenticação e verificação de area de cliente
*/
class LoginHelper
{
	private $_redir;
	public  $_auth;
	
	function __construct()
	{
		//INICIA O OBJETO DE REDIRECIONAMENTO
		$this -> _redir = new RedirectHelper();

		$this -> _auth = new AuthHelper();
		//DEFINE OS DADOS PARA O LOGIN
		$this -> _auth -> _tableName 		 = "clientes";
		$this -> _auth -> _userColumn		 = 'email';
		$this -> _auth -> _controllerLogado	 = 'clientes';
		$this -> _auth -> _actionLogado 	 = 'index';
		$this -> _auth -> _controllerErro    = 'clientes';
		$this -> _auth -> _actionErro 		 = 'login';
	}



	/**
	 * faz o login
	 * @return boolean
	 */
	public function login()
	{
		$this -> _auth ->  _user  =  $_POST['email'];
		$this -> _auth -> _senha  =  $_POST['senha'];
			
		//RETORNA FALSE PARA ERRO E TRUE PARA ACERTO
		if(!$this -> _auth -> login())
			return 'Login ou senha incorreta';
		else
			return $this -> _redir -> goToControllerAction( $this -> _auth -> _controllerLogado, $this -> _auth -> _actionLogado);
	}




	public function logout()
	{
		return $this -> _auth -> logout();
	}




	public function validaLogin()
	{
		if($this -> _redir -> getCurrentAction() != $this -> _auth -> _actionErro) 
		{
			if(!$this -> _auth -> verificaLogin())
			{
				$this -> _redir -> goToControllerAction($this -> _auth -> _controllerErro, $this -> _auth -> _actionErro);
			}
		}
	}
}