<?php

	/**
	* 
	*/
	class System extends Config
	{
		
		private $_url;
		private $_explode;
		public  $_controller;
		public  $_action;
		public  $_params;

		public function init()
		{
			$this->setUrl();
			$this->setExplode();
			$this->setController();
			$this->setAction();
			$this->setParams();
			$this->setGets();
		}

		private function setUrl()
		{
			//Atribui url à variavel _url
			$this->_url = (isset($_GET['url']) ? $_GET['url']  : $this->_Index . "/index_action" );
		}


		private function setExplode()
		{
			//cria um array com os valores passados pela url entre oa barras "/"
			$this->_explode = explode("/", $this->_url);
		}


		private function setController()
		{
			//Defino qual controller a ser usado
			$this->_controller = $this->_explode[0];
		}


		private function setAction()
		{
			//defino a action que será usada 
			$this->_action = (!isset($this->_explode[1]) || $this->_explode[1] == null || $this->_explode[1] == 'index' ? 'index_action' : $this->_explode[1] );
		}


		public function setParams()
		{
			unset($this->_explode[0], $this->_explode[1]);

			//retira ultimo valor, se for vazio
			if( end( $this->_explode ) == null )
				array_pop($this->_explode);

			$this->_params = $this->_explode;
		}



		public function setGets()
		{
			$url = $_SERVER['REQUEST_URI'];
			$url = explode("?", $url);

			if(isset($url[1]))
			{
				$urlParams = explode("&", $url[1]);

				foreach ($urlParams as $g) 
				{
					$get = explode("=", $g);
					$_GET[$get[0]] = $get[1];
				}
			}
		}



		public function run()
		{
			$controller_path = CONTROLLERS . $this->_controller . "Controller.php";

			if ( !file_exists( $controller_path ) ) 
			{
				$this->ERROR('controller');
			}

			require_once( $controller_path );
			$app = new $this->_controller();
			

			if (!method_exists($app, $this->_action)) 
			{
				$this->ERROR('action');
			}

			$action = $this->_action;

			if(empty($this->_params))
			{
				$app->init();
				$app->$action();
			}
			else
			{
				$i = 0;

				$valores = array();

				foreach ($this->_params as $p) 
				{
					$valores[$i] = $p;
					$i++;
				}
				$app->init($valores);
				$app->$action($valores);

			}
				

		}

	}