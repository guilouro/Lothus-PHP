<?php

class EmailHelper extends PHPMailer
{
	#PHPMail
	private $_mail	  = "";
	
	#Host
	private $_host	  = "";

	#Email HTML
	private $_html	  = true;

	#nome que aparecerá no envio 
	private $_nome	  = ""; 

	#email cadastrado para envio
	private $_from	  = "";

	#email que receberá o formulário
	private $_to	  = "";

	#usuario do email autenticado
	private $_usuario = "";

	#senha do email autenticado
	private $_senha   = "";


	#assunto do email
	private $_assunto = "";



	//GETTER AND SETTER
	public function __set($atrib, $value){  $this->$atrib = $value; }
	public function __get($atrib){ return $value; }
	


	public function __construct() 
	{

		#seta a linguagem
		$this-> SetLanguage("br", HELPERS . "/Email/language/");
	}
	
	
	public function enviarAutenticado($corpo)
	{
		

		#enviar via SMTP
		$this -> IsSMTP();

		#servidor SMTP
		$this -> Host = $this -> _host;

		#habilita smtp autenticado
		$this -> SMTPAuth = true;

		#usuário deste servidor smtp. Aqui esta a solucao
		$this -> Username = $this -> _usuario; // usuário
		$this -> Password = $this -> _senha; // senha

		#email utilizado para o envio, pode ser o mesmo de username
		$this -> From = $this -> _from; 
		$this -> FromName = $this -> _nome;
		

		$this -> AddReplyTo($this -> _from, $this -> _nome);

		#Enderecos que devem receber a mensagem
		$this -> AddAddress($this -> _to);


		$this -> IsHTML($this -> _html);
		$this -> Subject = $this -> _assunto;

		#adicionando o html no corpo do email
		$this -> Body = $corpo;

		#enviando e retornando o status de envio
		return $this -> Send();
	}


	public function enviar($corpo)
	{
		
		#enviar via SMTP
		$this -> IsMail();

		#email utilizado para o envio, pode ser o mesmo de username
		$this -> From = $this -> _from; 
		$this -> FromName = $this -> _nome;		

		#Endereço de email de resposta
		$this -> AddReplyTo($this -> _from, $this -> _nome);

		#Enderecos que devem receber a mensagem
		$this -> AddAddress($this -> _to);

		#Html ou texto
		$this -> IsHTML($this -> _html);
		
		#assunto do email
		$this -> Subject = $this -> _assunto;

		#adicionando o html no corpo do email
		$this -> Body = $corpo;

		#enviando e retornando o status de envio
		return $this -> Send();
	}
}
