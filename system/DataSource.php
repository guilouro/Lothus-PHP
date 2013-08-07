<?php
	/**
	 * Classe responsável pelo conexão ao banco do sistema
	 *
	 * @name DataSource
	 **/
	class DataSource
	{
		/**
		 * Conexão com o banco
		 *
		 * @name template
		 * @access protected
		 **/
		protected $bd;

		/**
		 * Construtor da classe.
		 * Conecta ao banco
		 *
		 * @name __construct
		 * @access public
		 * @param (string)$login Login de conexão ao SGBD
		 * @param (string)$senha Senha de conexão ao SGBD
		 * @param (string)$banco Banco ao qual se conectar
		 * @param (string)$host HOST de conexão ao SGBD
		 * @return void
		 * @version 1.03.22
		 **/
		public function __construct ( Array $dados )
		{

			extract( $dados );

			// Se passou login
			//
			if ( ! is_null ( $login ) )
			{
				// Conectando
				//
				$this -> conecta ( $login, $senha , $banco , $host );
			}

		}

		/**
		 * Método responsável por efetuar a conexão
		 *
		 * @name conecta
		 * @access public
		 * @param (string)$login Login de conexão ao SGBD
		 * @param (string)$senha Senha de conexão ao SGBD
		 * @param (string)$banco Banco ao qual se conectar
		 * @param (string)$host HOST de conexão ao SGBD
		 * @return (boolean)$sucesso - Booleano informando se teve sucesso
		 * @version 1.03.22
		 **/
		public function conecta ( $login, $senha , $banco , $host = 'localhost' )
		{
			// Conectando
			//
			@ $this -> bd = mysql_connect ( $host , $login, $senha );

			// Verifica se teve sucesso
			//
			$sucesso = $this -> getSeConectado ( );

			// Se teve sucesso
			//
			if ( $sucesso )
			{
				// Seleciona o banco
				//
				mysql_select_db ( $banco );
			}

			// Retorna se teve sucesso
			//
			return $sucesso;
		}

		/**
		 * Método que retorna se a conexão foi bem sucedida
		 *
		 * @name getSeConectado
		 * @access public
		 * @param
		 * @return (boolean) - Booleano informando se esta conectado
		 * @version 11.1.11
		 **/
		public function getSeConectado ( )
		{
			// Se teve sucesso
			//
			if ( $this -> bd )
			{
				// Retorna verdadeiro
				//
				return TRUE;
			}
			else
			{
				// Retorna falso
				//
				return FALSE;
			}
		}

		/**
		 * Executa um comando SQL
		 *
		 * @name executa
		 * @access public
		 * @param (string)$sql - Comando SQL a ser executado
		 * @param (boolean)$debug - Se deve debugar o comando. Por padrão é FALSE
		 * @return (connection) Conexão com o banco de dados MySQL
		 * @version 11.1.11
		 **/
		public function executa ( $sql , $debug = FALSE )
		{
			// Debuga (Se necessário)
			//
			$this -> debug ( $sql , $debug );

			// Executando e retornando
			//
			return mysql_query ( $sql );
		}

		/**
		 * Método que retorna o número de linhas afetadas por um comando SQL
		 *
		 * @name getLinhasAfetadas
		 * @access public
		 * @param
		 * @return (int) Número de linhas afetadas pelo último comando SQL
		 * @version 11.1.11
		 **/
		public function getLinhasAfetadas ( )
		{
			// Retornando
			//
			return mysql_affected_rows ( );
		}

		/**
		 * Retorna o resultado de uma consulta SQL
		 *
		 * @name consulta
		 * @access public
		 * @param (string)$sql - Comando SQL a ser executado
		 * @param (boolean)$debug - Se deve debugar o comando. Por padrão é FALSE
		 * @return (array)$resultado - Array com os resultados da consulta SQL
		 * @version 11.1.11
		 **/
		public function consulta ( $sql , $debug = FALSE )
		{
			// Declarando array de resultado
			//
			$resultado = array ( );

			// Debuga (Se necessário)
			//
			$this -> debug ( $sql , $debug );

			// Consultando
			//
			$tabela = mysql_query ( $sql );

			// Percorrendo todas as linhas da tabela de resultado
			//
			for ( $contador = 0 ;  $linha = mysql_fetch_assoc ( $tabela ) ; $contador++ )
			{
				// Percorrendo todas as colunas da linha
				//
				foreach ( $linha as $coluna => $valor )
				{
					// Inserindo em Array de resultado
					//
					$resultado [ $contador ] [ $coluna ] = $valor;
				}
			}

			// Retornando resultado
			//
			return $resultado;
		}

		/**
		 * Consulta uma única linha
		 * Este método retorna a primeira linha do resultado de uma consulta
		 *
		 * @name consultaLinha
		 * @access public
		 * @param (string)$sql - Comando SQL a ser executado
		 * @param (boolean)$debug - Se deve debugar o comando. Por padrão é FALSE
		 * @return (array)$resultado - Array com os resultados da consulta SQL
		 * @version 11.1.11
		 **/
		public function consultaLinha ( $sql , $debug = FALSE )
		{
			// Consultando e retornando
			//
			return array_shift ( $this -> consulta ( $sql , $debug ) );
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
			//
			$resultado = $this -> consultaLinha ( $sql , $debug );

			// Se retornou um Array
			//
			if ( is_array ( $resultado ) )
			{
				// Retornando a primeira coluna
				//
				return array_shift ( $resultado );
			}
			else
			{
				// Retornando falso
				//
				return FALSE;
			}
		}

		/**
		 * Método que retorna o ID gerado pela operação INSERT anterior
		 *
		 * @name getUltimoId
		 * @access public
		 * @param
		 * @return (int) - Id do último INSERT SQL
		 * @version 11.1.11
		 **/
		public function getUltimoID ( )
		{
			// Retornando
			//
			return mysql_insert_id ( );
		}

		/**
		 * Método privado de debug dos comandos SQL
		 *
		 * @name debug
		 * @access private
		 * @param (string)$sql - Comando SQL a ser debugado
		 * @param (boolean)$debug - Booleano informando se deve debugar
		 * @return void
		 * @version 11.1.11
		 **/
		private function debug ( $sql , $debug )
		{
			// Se deve debugar
			//
			if ( $debug )
			{
				// Escrevendo comando SQL
				//
				echo "<hr/><pre>$sql</pre><hr/>";
			}
		}
	}
?>
