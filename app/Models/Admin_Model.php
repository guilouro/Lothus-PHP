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

	}