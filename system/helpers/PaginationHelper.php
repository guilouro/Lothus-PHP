<?php


class PaginationHelper
{
	private $_model;
	private $_limite;
	private $_paginaAtual;
	private $_inicio;
	private $_total_registros;



	//GETTER AND SETTER
	public function __set($atrib, $value){  $this->$atrib = $value; }
	public function __get($atrib){ return $this->$atrib; }	


	/**
	 * Classe para paginação
	 * @param String $tabela tabela no banco de dados para a consulta
	 */
	function __construct( $tabela )
	{
		
		$this -> _model = new Model();
		$this -> _model -> _tabela = $tabela;
	}



	/**
	 * Pega a quantidade total de páginas de acordo com a lista
	 * @return int retorna a quantidade de páginas
	 */
	public function totalPaginas()
	{
		return Ceil( $this -> _total_registros / $this -> _limite );
	}





	/**
	 * Responsável por fazer a consulta limitada para a paginação e definição de alguns atributos
	 * @param  Array $arr parâmetros para a consulta SQL. 'where' , 'orderby'
	 * @return Array      retorna a consulta limitada
	 */
	public function consulta(Array $arr = null)
	{
		// $inner = $this -> model -> innerJoin();

		# DEFINE O LIMIT INICIAL DE ACORDO COM A PÁGINA ATUAL E O LIMIT DEFINIDO
		$this -> _inicio = ($this -> getPaginaAtual() * $this -> _limite) - $this -> _limite;
		
		# OPÇÕES PARA CONSULTA SQL
		$where   = (isset($arr['where'])   AND !empty($arr['where']))   ? "WHERE " . $arr['where'] : "";
		$orderby = (isset($arr['orderby']) AND !empty($arr['orderby'])) ? "ORDER BY " . $arr['orderby'] : "";
		$inner   = (isset($arr['inner']) AND !empty($arr['inner'])) ? $arr['inner'] : "";

		# STRING DE CONSULTA 
		$sql = "SELECT * FROM `{$this -> _model -> _tabela}` {$inner} {$where} {$orderby}";

		# PEGA A QUANTIDADE DE REGISTROS DA CONSULTA
		$this -> _total_registros = count($this -> _model -> consulta($sql));

		# RETORNA A CONSULTA LIMITADA 
		return $this -> _model -> consulta($sql . " LIMIT " . $this -> _inicio . "," . $this -> _limite);
	}



	/**
	 * Retorna a página atual 
	 * @return int
	 */
	public function getPaginaAtual()
	{
		return (int)str_replace("pag", "", $this -> _paginaAtual);
	}



	/**
	 * Gera url atual
	 * @return string retorna a url até a parte da pagina
	 */
	public function getUrl()
	{
		$redir    =  new RedirectHelper();
		$url 	  =  URL . $redir -> getCurrentController() . "/" . $redir -> getCurrentAction() . "/" . $redir -> getUrlParams( true );


		// RETIRA A ULTIMA BARRA CASO EXISTA
		if($url[ strlen($url) - 1 ] == "/")
			$url = substr($url, 0, -1);

		// DIVIDE A URL
		$paramns2 =  explode("/", $url);

		//RETIRA A PAGINA DA URL
		if(substr(end($paramns2), 0, 3) == "pag")
			array_pop($paramns2);

		//JUNTA O ARRAY EM UMA STRING E RETORNA O RESULTADO
		return implode("/", $paramns2);
	}



	/**
	 * Cria a view para exibição da paginação
	 * @param  string $classe define a classe da div que engloba a paginação
	 * @return string 
	 */
	public function view($classe = "pagination pagination-centered")
	{
		$str = '';


		if($this -> _total_registros > $this -> _limite)
		{
			# PEGA URL ATUAL
			$url = $this -> getUrl();

			# STRING QUE VAI RETORNA A PAGINACAO
			$str  = "<div class='" . $classe . "'><ul>";

			# SE ESTIVER NA PRIMEIRA PÁGINA ELE TIRA A SETA
			if($this -> getPaginaAtual() > 1)
				$str .= "<li><a href='" . $url . "/pag" . ($this -> getPaginaAtual() - 1) . "'>«</a></li>";

			# VERIFICA A QUANTIDADE DE PAGINAS E A PAGINA ATUAL
			for ($i=1; $i <= $this -> totalPaginas() ; $i++) 
			{ 
				# SE FOR A PÁGINA ATUAL COLOCA ATIVO
				if($this -> getPaginaAtual() == $i)
					$str .= "<li class='active'><a>$i</a></li>";
				else
					$str .= "<li><a href='" . $url . "/pag$i'>$i</a></li>";
			}

			# SE ESTIVER NA ÚLTIMA PÁGINA ELE TIRA A SETA
			if($this -> getPaginaAtual() < $this -> totalPaginas())
				$str .= "<li><a href='" . $url . "/pag" . ($this -> getPaginaAtual() + 1) . "'>»</a></li>";
		}


		$str .= "</ul></div>";

		return $str;
	}
}