<?php

/**
* Class para redirecionamento
*/
class RedirectHelper
{
	protected $parameters = array();
	
	protected function go( $data )
	{
		header("Location: " . URL . $data);
		exit();
	}

	public function getCurrentController()
	{
		global $start;
		return $start->_controller;
	}

	public function getCurrentAction()
	{
		global $start;
		return $start->_action;
	}

	public function goToController( $controller )
	{
		$this->go($controller . "/index/" . $this->getUrlParams());
	}

	public function goToAction( $action, $paramsGlobal = FALSE )
	{
		$this->go($this->getCurrentController() . "/" . $action . "/" . $this->getUrlParams( $paramsGlobal ));
	}

	public function goToControllerAction( $controller, $action )
	{
		$this->go($controller . "/" . $action . "/" . $this->getUrlParams());
	}


	public function setUrlParams( $arr )
	{
		global $start;
		$start -> _params[] = $arr;
		$this->parameters[] = $arr;
	}


	public function getUrlParams( $paramsGlobal = FALSE )
	{
		global $start;

		$params = '';

		if( $paramsGlobal )
			$arr = $start -> _params;
		else
			$arr = $this -> parameters;

		foreach ($arr as $p) 
		{
			$params .= $p . "/";
		}

		return $params;
	}
}