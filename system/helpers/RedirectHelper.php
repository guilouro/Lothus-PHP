<?

/**
* Class para redirecionamento
*/
class RedirectHelper
{
	protected $parameters = array();
	
	protected function go( $data )
	{
		header("Location: " . URL . $data);
	}

	protected function getCurrentController()
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

	public function goToAction( $action )
	{
		$this->go($this->getCurrentController() . "/" . $action . "/" . $this->getUrlParams());
	}

	public function goToControllerAction( $controller, $action )
	{
		$this->go($controller . "/" . $action . "/" . $this->getUrlParams());
	}


	public function setUrlParams( $arr )
	{
		$this->parameters[] = $arr;
	}


	public function getUrlParams( $arr )
	{
		$params = '';
		foreach ($this->parameters as $p) 
		{
			$params .= $p . "/";
		}

		return $params;
	}
}

?>