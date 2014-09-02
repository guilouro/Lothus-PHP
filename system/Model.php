<?php

/*
 * Padrão TableData Gateway
 */

class Model
{
	protected $db;
	public    $_tabela;
	public    $_fk;

	public function __construct() 
	{
		extract(DATABASE_CONFIG::$default);
		$this -> db = new PDO("mysql:host=$host;dbname=$banco", "$login", "$senha", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
		if(!$this -> db) die('Erro ao conectar ao banco de dado');
	}





	/**
	 * Inserir dados no banco
	 *
	 * @name insert
	 * @access public
	 * @param (array)$dados - Array com os dados para inserir na tabela
	 * @param (boolean)$where - Condição passada para atualização
	 * @return (int) retorna a id da linha inserida;
	 * @version 1.0
	 **/
	public function insert( Array $dados, $debug = FALSE )
	{
		$fields = $this -> getTableField();

		foreach ($dados as $ind => $val)
		{
			if(in_array($ind, $fields))
			{
				$dadosBD[$ind] = $val;
			}
		}

		//INSERT DINAMICO BASEADO EM ARRAY DE DADOS
		$campos	  =  implode(",", array_keys($dadosBD));
		$valores  =  ":" . implode(",:", array_keys($dadosBD));


		//FAZ O DEBUG DA STRING SQL
		if($debug)
		{
			$valoresDebug = "'" . implode("','", $dadosBD) . "'";
			$this -> debug ( "INSERT INTO `{$this->_tabela}` ({$campos}) values ({$valoresDebug})" );
		}

		//TENTA INSERIR OS DADOS
		try 
		{
			//PREPARA OS DADOS PARA INSERT USANDO PREPARED STATEMENTS
			$ps = $this -> db -> prepare("INSERT INTO `{$this->_tabela}` ({$campos}) values ({$valores})");
			
			//PASSA OS VALORES CORRETOS BASEADOS NO PARAMETROS DA STRING
			foreach ($dadosBD as $key => $value){
			 	$ps->bindValue(":$key", $value);
			}

			//EXECUTA A QUERY
			$executa = $ps->execute();

		} catch (PDOexception $e) {
			echo $e->getMessage();
		}

		return $this -> db ->lastInsertId();
	}





	/**
	 * Consulta direcionada a tabela atual
	 *
	 * @name read
	 * @access public
	 * @param (string)$where   - Passa condições se for necessário
	 * @param (string)$limit   - define o limit da pesquisa
	 * @param (string)$offset  - define o offset da pesquisa
	 * @param (string)$orderby - define a ordem para o resultado
	 * @param (boolean)$debug  - imprime a string do sql
	 * @version 1.0
	 **/
	public function read( $where = null , $limit = null , $offset = null , $orderby = null, $debug = FALSE )
	{
		$where    = ($where   != null ? "WHERE {$where}"      : "");
	 	$limit    = ($limit   != null ? "LIMIT {$limit}" 	  : "");
	 	$offset   = ($offset  != null ? "OFFSET {$offset}" 	  : "");
	 	$orderby  = ($orderby != null ? "ORDER BY {$orderby}" : "");

	 	if($debug)
	 		$this -> debug("SELECT * FROM `{$this->_tabela}` {$where} {$orderby} {$limit} {$offset}");

	 	$query = $this -> db -> prepare("SELECT * FROM `{$this->_tabela}` {$where} {$orderby} {$limit} {$offset}");
	 	$query -> execute();
	 	return $query -> fetchAll(PDO::FETCH_ASSOC);
	}



	/**
	 * Consulta direcionada a tabela atual com retorno de uma linha
	 *
	 * @name read
	 * @access public
	 * @param (string)$where   - Passa condições se for necessário
	 * @param (string)$limit   - define o limit da pesquisa
	 * @param (string)$offset  - define o offset da pesquisa
	 * @param (string)$orderby - define a ordem para o resultado
	 * @param (boolean)$debug  - imprime a string do sql
	 * @version 1.0
	 **/
	public function readLine( $where = null , $limit = null , $offset = null , $orderby = null, $debug = FALSE )
	{
		$where    = ($where   != null ? "WHERE {$where}"      : "");

		
		if($debug)
	 		$this -> debug("SELECT * FROM `{$this->_tabela}` {$where}");
	 	

	 	$query = $this -> db -> prepare("SELECT * FROM `{$this->_tabela}` {$where}");
	 	$query -> execute();
	 	return $query -> fetch(PDO::FETCH_ASSOC);
	}





	/**
	 * Atualiza dos dados da tabela
	 *
	 * @name update
	 * @access public
	 * @param (array)$dados - Array com os novos dados da tabela
	 * @param (boolean)$where - Condição passada para atualização
	 * @version 1.0
	 **/
	public function update( Array $dados, $where, $debug = FALSE )
	{
		$fields = $this -> getTableField();

		foreach ($dados as $ind => $val)
		{
			if(in_array($ind, $fields))
			{
				$campos[] = "{$ind} = :{$ind}";
				$dadosBD[$ind] = $val;
			}
		}


		$campos = implode(",", $campos);



		//FAZ O DEBUG DA STRING SQL
		if($debug)
		{
			foreach ($dadosBD as $key => $value){ $camposDebug[] = "$key = '$value'"; }
			$camposDebug = implode(",", $camposDebug);
			$this -> debug ( "UPDATE `{$this->_tabela}` SET {$camposDebug} WHERE {$where}" );
		}


		try 
		{
			//PREPARA OS DADOS PARA INSERT USANDO PREPARED STATEMENTS
			$ps = $this -> db -> prepare("UPDATE `{$this->_tabela}` SET {$campos} WHERE {$where}");
			
			//PASSA OS VALORES CORRETOS BASEADOS NO PARAMETROS DA STRING
			foreach ($dadosBD as $key => $value){
			 	$ps->bindValue(":$key", $value);
			}

			//EXECUTA A QUERY
			$executa = $ps->execute();

		} catch (PDOexception $e) {
			echo $e->getMessage();
		}

		return $ps->rowCount();
	}




	/**
	 * Inserir dados no banco
	 *
	 * @name delete
	 * @access public
	 * @param (boolean)$where - Condição passada para exclusão
	 * @return (int) retorna a quantidade de linhas afetadas;
	 * @version 1.0
	 **/
	public function delete( $where )
	{
		try 
		{
			$ps = $this -> db -> prepare("DELETE FROM `{$this->_tabela}` WHERE {$where} ");
			$executa = $ps -> execute();

		} catch (PDOexception $e) {
			echo $e->getMessage();
		}

		return $ps->rowCount();
	}




	/**
	 * Retorna o nome dos campos da tabela no banco de daos
	 *
	 * @name getTableField
	 * @access private
	 * @version 1.0
	 **/
	private function getTableField()
	{

		$fields = array();
		$result = $this -> db ->query("DESCRIBE `{$this->_tabela}`")->fetchAll();

		foreach ($result as $r) {
			array_push($fields, $r['Field']);
		}

		return $fields;

	}



	/**
	 * Retorna o resultado de uma consulta SQL
	 *
	 * @name consulta
	 * @access public
	 * @param (string)$sql - Comando SQL a ser executado
	 * @param (boolean)$debug - Se deve debugar o comando. Por padrão é FALSE
	 * @version 1.0
	 **/
	public function consulta($sql, $debug = FALSE)
	{
		if($debug) $this -> debug($sql);
		return $this -> db -> query($sql) -> fetchAll();
	}




	/**
	 * Consulta uma única linha
	 * Este método retorna a primeira linha do resultado de uma consulta
	 *
	 * @name consultaLinha
	 * @access public
	 * @param (string)$sql - Comando SQL a ser executado
	 * @param (boolean)$debug - Se deve debugar o comando. Por padrão é FALSE
	 * @return (array)$resultado - Array com os resultados da primeira linha da consulta SQL
	 * @version 11.1.11
	 **/
	public function consultaLinha ( $sql , $debug = FALSE )
	{
		if($debug) $this -> debug($sql);

		$query = $this -> db -> prepare($sql);
		$query -> execute();

		return $query -> fetch();
	}





	/**
	 * Consulta um único valor
	 * Este método retorna a primeira coluna da primeira linha do resultado de uma consulta
	 *
	 * @name consultaValor
	 * @access public
	 * @param (string)$sql - Comando SQL a ser executado
	 * @param (boolean)$debug - Se deve debugar o comando. Por padrão é FALSE
	 * @return (?) Valor da primeira coluna da primeira linha do resultado da consulta
	 * @version 11.1.11
	 **/
	public function consultaValor ( $sql , $debug = FALSE )
	{
		// Consultando
		$resultado = $this -> consultaLinha ( $sql , $debug );

		// Se retornou um Array
		if ( is_array ( $resultado ) )
		{
			// Retornando a primeira coluna
			return array_shift ( $resultado );
		}
		else
		{
			// Retornando falso
			return FALSE;
		}
	}



	/**
	 * Busca os dados de uma tabela relacionada atravez da chave estrangeira
	 *
	 * @name populateFK
	 * @access public
	 * @return (array) retorna um array com os dados referente a cada tabela relacionada
	 **/
	public function populateFK()
	{
		foreach ($this -> fk as $key => $value) 
		{
			$order = (isset($this -> orderby[$key])) ? "ORDER BY " . $this -> orderby[$key] : "";
			$dados[$key] = $this -> consulta("SELECT * FROM `{$key}` {$order}");
		}

		return $dados;
	}




	/**
	 * Método privado de debug dos comandos SQL
	 *
	 * @name debug
	 * @access private
	 * @param (string)$sql - Comando SQL a ser debugado
	 * @return void
	 * @version 1
	 **/
	private function debug ( $sql )
	{
		echo "<hr/><pre>$sql</pre><hr/>";
	}
}