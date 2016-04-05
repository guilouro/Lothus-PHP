<?php

/**
* 
*/
class Config 
{
	
	public  $html;



	/*
	 * Variável que define a pasta para a pasta inicial.
	 * O FRAMEWORK reconhecerá a string passada como sendo o arquivo index do projeto.
	 */

	public  $_Index = "home";



	/*
	 * Variável que define a exibição de erros.
	 * TRUE: Exibe os erros nativos do PHP e SQL, além de error gerados pelo FRAMEWORK
	 * FALSE: Bloqueia a exibição de todos os erros, tanto do PHP e SQL quanto do FRAMEWORK
	 */

	private $error = TRUE;





	
	protected function ERROR($pag)
	{

		if($this->error)
		{
			switch ($pag) 
			{
			case 'controller':
				die("O Controller " . $this->_controller . " não foi criado.");
				break;
			
			case 'action':
				die("A action " . $this->_action . " não foi criada.");

			default:
				break;
			}
		}
		else
		{
			error_reporting(0);
			header('Location:' . URL . 'erro/pagina_nao_encontrada');
		}
	}
}