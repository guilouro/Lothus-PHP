<?php

	error_reporting(0);
	session_start();

	/*
	 * Setar hora do servidor
	 */
	date_default_timezone_set("Brazil/East");



	/*
	 * Define DS como o separador de diretorios (/) para usar em outros locais
	 */
	if (!defined('DS')) 
		define('DS', DIRECTORY_SEPARATOR);

	/**
	 * Define um caminho completo da aplicacao
	 */
	if (!defined('ROOT')) 
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	
	/**
	 * Define o diretorio atual da aplicação
	 */
	if (!defined('APP_DIR')) 
		define('APP_DIR', basename(dirname(dirname(__FILE__))));


	$_SERVER['PHP_SELF'] = str_replace("app/webroot/index.php", "", $_SERVER['PHP_SELF']);

	define('URL', $_SERVER['PHP_SELF']);
	define('IMG', URL . "img/");
	define('JS', URL . "js/");
	define('CSS', URL . "css/");
	


	define( 'LIB', '../../app/Lib/' );
	define( 'FILES', '../../app/webroot/files/' );
	define( 'CONTROLLERS', '../../app/Controllers/' );
	define( 'VIEWS', '../../app/Views/' );
	define( 'LAYOUT', '../../app/Views/Layouts/' );
	define( 'MODELS', '../../app/Models/' );
	define( 'HELPERS', '../../system/helpers/' );
	define( 'SYSTEM', '../../system/' );
	define( 'CONFIG', '../../app/Config/' );
	
	require_once('../../system/System.php');
	require_once('../../system/Controller.php');
	require_once('../../system/Model.php');
	require_once('../../system/TemplateParser.php');

	function __autoload( $file )
	{
		if( file_exists(MODELS . $file . ".php"))
			require_once( MODELS . $file . ".php");
		else if( file_exists(HELPERS . $file . ".php"))
			require_once( HELPERS . $file . ".php");
		else if( file_exists(HELPERS . "/Email/" . $file . ".php"))
			require_once( HELPERS . "/Email/" . $file . ".php");
		else if( file_exists(SYSTEM . $file . ".php"))
			require_once( SYSTEM . $file . ".php");
		else if( file_exists(CONTROLLERS . $file . "Controller.php"))
			require_once( CONTROLLERS . $file . "Controller.php");
		else if( file_exists(LIB . $file . ".php"))
			require_once( LIB . $file . ".php");
		else if( $file == "DATABASE_CONFIG")
			require_once( CONFIG . "database.php");
		else
			die($file . " não encontrado" );
	}

	$start = new System;
	$start->init();
	$start->run();