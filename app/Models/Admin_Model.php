<?php
	class Admin_Model extends Model
	{
		public $_tabela = 'developer';

		public $fk = array(
			// 'categorias' => 'id_categorias',
		);


		public $orderby = array(
			// 'titulo' => 'data DESC',
		);


		public $pagination = array(
			'contato'	=> array('limit' => 5)
		);


		public function getCurrentUser()
		{
			return $this->consultaLinha("SELECT * FROM developer WHERE id_developer = " .$_SESSION['dados_usuario']['id_developer']);
		}
	}