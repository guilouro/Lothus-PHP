<?php

class admin extends Controller
{
	protected $bd;
	protected $redir;
	private   $_auth;
	private   $_dados;
	private   $_orderby;
	public 	  $pagination;

	public function init($params = null)
	{
		$this->_dados[] = '';
		$this->_orderby = '';


		//INICIA O OBJETO DE REDIRECIONAMENTO
		$this->redir = new RedirectHelper();

		
		//ESCOLHE O LAYOUT E O MODEL DO ADMN
		$this->_layout = 'admin';
		$this->bd = new Admin_Model();


		//AUTENTICAÇÃO
		$this -> _auth = new AuthHelper();
		$this -> _auth -> _tableName = "developer";

		if($this->redir->getCurrentAction() != 'login') 
		{
			if(!$this -> _auth -> verificaLogin())
			{
				$this -> redir -> goToAction('login');
			}
		}




		//DEFINE A TABELA PARA OS USUARIOS
		if($params[0] != "usuarios")
			$this ->bd->_tabela = $params[0];




		//VERIFICA ORDEM DE EXIBIÇÃO
		if(isset($this -> bd -> orderby[$this -> bd -> _tabela]))
			$this -> _orderby = $this -> bd -> orderby[$this -> bd -> _tabela];

	}

	

	//PAGINA INICIAL
	public function index_action($params = null)
	{
		$this-> bd -> _tabela = 'produtos';
		$dados['lista'] = $this-> bd -> read(null, "5", null, 'id_produtos DESC');
		$this->view('index', $dados);
	}



	//PÁGINA PARA LISTAR OS ITENS DO BANCO DE DADOS
	public function lista($params = null)
	{
		//SE EXISTIR CHAVE ESTRANGEIRA ELE LISTA
		//PARA ENVIAR PARA A VIEW
		if(isset($this->bd->fk))
		{
			foreach ($this->bd->fk as $key => $value) 
			{
				$order = (isset($this -> bd -> orderby[$key])) ? "ORDER BY " . $this -> bd -> orderby[$key] : "";
				$this->_dados[$key] = $this->bd->consulta("SELECT * FROM `{$key}` {$order}");
			}
		}



			
		if(isset($this -> bd -> pagination[ $params[0] ]))
		{
			// PAGINAÇÃO
			$this -> pagination = new PaginationHelper( $params[0] );
			$this -> pagination -> _paginaAtual = (isset($params[1])) ? $params[1] : "pag1";
			$this -> pagination -> _limite = $this -> bd -> pagination[ $params[0] ]['limit'];
			$this->_dados['info'] = $this -> pagination -> consulta( array( 'orderby' => $this -> _orderby ) );
			// FIM PAGINAÇÃO
		}
		else
		{
			$this->_dados['info'] = $this->bd->read(null, null, null, $this -> _orderby);
		}

		$this->view("lista_" . $params[0], $this->_dados);
	}



	//MÉTODO PARA ADD AO BANCO DE DADOS
	public function add($params = null)
	{
		//SE TENTAR ADD ARQUIVO ELE ENTRA AQUI
		if(!empty($_FILES))
		{
			//PEGO O NOME DA CHAVE DO ARRAY $_FILES
			$key = array_keys($_FILES);
			
			//SE NÃO OCORRER NENHUM ERRO ELE FAZ O UPLOAD
			if(!($_FILES[$key[0]]['error']))
			{
				//CRIO O OBJETO DEFININDO O NOME DA PASTA
				$upload = new ImageHelper( FILES );
				//FAZ O UPLOAD E RETORNA O NOME DO ARQUIVO INSERINDO NO ARRAY $_POST PARA UPLOAD
				$_POST[$key[0]] = $upload -> ResizeByUpload( $_FILES[ $key[0] ]);
			}
		}

		//SE EXISTIR DADOS EM POST ADD AO BANCO
		if($_POST)
		{

			//SE EXISTIR CAMPO SENHA ELE CODIFICA
			if(isset($_POST['senha'])) $_POST['senha'] = hash('sha512', $_POST['senha']);

			if($this -> bd -> insert($_POST))
				$this->_dados['status'] = 'Inserido com sucesso';
		}


		//SE EXISTIR CHAVE ESTRANGEIRA ELE LISTA
		//PARA ENVIAR PARA A VIEW
		if(isset($this->bd->fk))
		{
			foreach ($this->bd->fk as $key => $value) 
			{
				$order = (isset($this -> bd -> orderby[$key])) ? "ORDER BY " . $this -> bd -> orderby[$key] : "";
				$this->_dados[$key] = $this->bd->consulta("SELECT * FROM `{$key}` {$order}");
			}
		}

		$this->view($params[0], $this->_dados);

	}


	public function edit($params = null)
	{
		$where = 'id_' . $this->bd->_tabela . ' = ' . $params[1];
		$this->_dados['id'] = $params[1];

		//SE TENTAR EDITAR O ARQUIVO ELE ENTRA AQUI
		if(!empty($_FILES))
		{
			//PEGO O NOME DA CHAVE DO ARRAY $_FILES
			$key = array_keys($_FILES);
			
			//SE NÃO OCORRER NENHUM ERRO ELE FAZ O UPLOAD
			if(!($_FILES[$key[0]]['error']))
			{
				//EXCLUI O ARQUIVO ALTERADO
				$imagem = $this -> bd -> readLine($where);
				unlink( FILES . $imagem[$key[0]]);

				//CRIO O OBJETO DEFININDO O NOME DA PASTA
				$upload = new ImageHelper( FILES );
				//FAZ O UPLOAD E RETORNA O NOME DO ARQUIVO INSERINDO NO ARRAY $_POST PARA UPLOAD
				$_POST[$key[0]] = $upload -> ResizeByUpload( $_FILES[ $key[0] ]);
			}
		}
		
		if($_POST)
		{
			//SE EXISTIR CAMPO SENHA ELE CODIFICA
			if(isset($_POST['senha'])) 
			{
				if(!empty($_POST['senha']))
					$_POST['senha'] = hash('sha512', $_POST['senha']);
				else
					unset($_POST['senha']);
			}

			//FAZ A ATUALIZAÇÃO
			if($this -> bd -> update($_POST, $where))
				$this->_dados['status'] = 'Atualizado com sucesso';
		}

		
		//PARA ENVIAR PARA A VIEW
		if(isset($this->bd->fk))
		{
			foreach ($this->bd->fk as $key => $value) 
			{
				$order = (isset($this -> bd -> orderby[$key])) ? "ORDER BY " . $this -> bd -> orderby[$key] : "";
				$this->_dados[$key] = $this->bd->consulta("SELECT * FROM `{$key}` {$order}");
			}
		}


		
		$this->_dados['dados'] = $this -> bd -> readLine($where);

		//SE EXISTIR CAMPO SENHA ELE CODIFICA
		//if(isset($this->_dados['dados']['senha'])) $this->_dados['dados']['senha'] = $this->_auth->decodifica($this->_dados['dados']['senha']);

		$this->view($params[0], $this->_dados);
	}



	public function del($params = null)
	{
		//DELETA A LINHA
		$where = 'id_' . $this->bd->_tabela . ' = ' . $params[1];
		$this -> bd ->delete($where);

		//REDIRECIONA
		$this->redir->setUrlParams($params[0]);
		$this->redir->goToAction('lista');
	}



	public function login($params = null)
	{
		$dados = '';

		if($_POST)
		{
			//DEFINE OS DADOS PARA O LOGIN
			$this -> _auth -> _user  	 = $_POST['login'];
			$this -> _auth -> _senha 	 = $_POST['senha'];


			//RETORNA FALSE PARA ERRO E TRUE PARA ACERTO
			if(!$this -> _auth -> login())
				$dados['msg'] = 'Login ou senha incorreta';
			else
				$this->redir->goToAction('index');
		}


		$this->_layout = 'login';
		$this->view('login', $dados);

	}



	public function logout($params = null)
	{
		$this -> _auth -> logout();
	}



	public function getValor($tabela, $valor, $id)
	{
		$this -> bd -> _tabela = $tabela;
		return $this -> bd -> consultaValor("SELECT {$valor} FROM {$tabela} WHERE id_" . $tabela . " = " . $id);
	}


	public function getLinha($tabela, $id)
	{
		$this -> bd -> _tabela = $tabela;
		return $this -> bd -> consultaLinha("SELECT * FROM {$tabela} WHERE id_" . $tabela . " = " . $id);
	}

}