<?php

class Controller extends System
{

	public $_layout = 'default';

	protected function view( $nome_pagina, $vars = null )
	{

		if ( is_array($vars) && count($vars) > 0 ) 
			extract( $vars );

		//instancia a classe
		$tp = new TemplateParser(LAYOUT . $this->_layout . ".phtml");

		//verifica qual a pasta que estará a view
		$act = explode("-", current(explode("/", (isset($_GET['url']) ? $_GET['url']  : $this->_Index."/index_action" ))));

		foreach ($act as $key => $value)
			$act[$key] = ucfirst($value);

		//verifica qual a pasta que estará a view
		$pasta = implode("", $act);

		ob_start();
        include(VIEWS . "$pasta/$nome_pagina.phtml");
        //O conteúdo deste buffer interno é copiado na variável $contento
        $conteudo  =  ob_get_contents();

        ob_end_clean();

		 
		//define os parâmetros da classe
		$tags = array(  'conteudo' => $conteudo);
			 
		//faz a substituição
		$tp->parseTemplate($tags);
		 
		// exibe a page
		echo $tp->display();
	}

	public function init(){}

}