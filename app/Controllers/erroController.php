<?php

class Erro extends Controller
{

	public function index_action($params = null)
	{
		$this->view('404');
	}

	public function pagina_nao_encontrada($params = null)
	{
		$this->view('404');
	}

}