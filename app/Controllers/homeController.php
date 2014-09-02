<?php

class home extends Controller
{

	public function init($params = null)
	{
		
	}


	public function index_action($params = null)
	{
		$this->view('index');
	}

}