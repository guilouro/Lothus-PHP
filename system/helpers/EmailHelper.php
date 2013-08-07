<?

class EmailHelper
{
	#PHPMail
	private $_mail	  = "";
	
	#Host
	private $_rost	  = "";

	#Email HTML
	private $_html	  = false;

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
		#instancia o objeto
		$this -> _mail = new PHPMailer();

		#seta a linguagem
		$this -> _mail-> SetLanguage("br", HELPERS . "/Email/language/");
	}
	
	
	public function enviarAutenticado($corpo)
	{
		

		#enviar via SMTP
		$this -> _mail -> IsSMTP();

		#servidor SMTP
		$this -> _mail -> Host = $this -> _host;

		#habilita smtp autenticado
		$this -> _mail -> SMTPAuth = true;

		#usuário deste servidor smtp. Aqui esta a solucao
		$this -> _mail -> Username = $this -> _usuario; // usuário
		$this -> _mail -> Password = $this -> _senha; // senha

		#email utilizado para o envio, pode ser o mesmo de username
		$this -> _mail -> From = $this -> _from; 
		$this -> _mail -> FromName = $this -> _nome;
		

		$this -> _mail -> AddReplyTo($this -> _from, $this -> _nome);

		#Enderecos que devem receber a mensagem
		$this -> _mail -> AddAddress($this -> _to);


		$this -> _mail -> IsHTML($this -> _html);
		$this -> _mail -> Subject = $this -> _assunto;

		#adicionando o html no corpo do email
		$this -> _mail -> Body = $corpo;

		#enviando e retornando o status de envio
		return $this -> _mail -> Send();
	}


	public function enviar($corpo)
	{
		
		#enviar via SMTP
		$this -> _mail -> IsMail();

		#email utilizado para o envio, pode ser o mesmo de username
		$this -> _mail -> From = $this -> _from; 
		$this -> _mail -> FromName = $this -> _nome;		

		#Endereço de email de resposta
		$this -> _mail -> AddReplyTo($this -> _from, $this -> _nome);

		#Enderecos que devem receber a mensagem
		$this -> _mail -> AddAddress($this -> _to);

		#Html ou texto
		$this -> _mail -> IsHTML($this -> _html);
		
		#assunto do email
		$this -> _mail -> Subject = $this -> _assunto;

		#adicionando o html no corpo do email
		$this -> _mail -> Body = $corpo;

		#enviando e retornando o status de envio
		return $this -> _mail -> Send();
	}
}

?>