<?php

class Contato extends Controller
{

	public function index_action($params = null)
	{
		$dados ='';

		if($_POST)
		{
			#DATA DO ENVIO
			$_POST['data'] = date('Y-m-d H:i:s');

			
			// #PREPARA ENVIO AUTENTICADO
			// $email = new EmailHelper();
			// $email -> _host    = "smtp.gmail.com";
			// $email -> _usuario = "guipclouro@gmail.com";
			// $email -> _senha   = "";
			// $email -> _nome    = $_POST['nome'];
			// $email -> _from    = $_POST['email'];
			// $email -> _to 	  = "guipclouro@gmail.com";
			// $email -> _assunto = $_POST['assunto'];


			#PREPARA ENVIO COMUM
			$email = new EmailHelper();
			$email -> _nome    = $_POST['nome'];
			$email -> _from    = $_POST['email'];
			$email -> _to 	   = "guipclouro@gmail.com";
			$email -> _assunto = $_POST['assunto'];


			#FAZ O ENVIO
			if($email -> enviar($_POST['mensagem']))
			{
				$dados['status'] = "<i class='icon-ok-sign'></i> Mensagem enviada com sucesso!";
				$dados['alert']  = 'alert-success';

				#INSERE CONTATO NO BANCO DE DADOS
				$db = new Contato_Model();
				$db -> insert($_POST);
			}
			else
			{
				$dados['status'] = "<i class='icon-ban-circle'></i> Ocorreu um erro ao enviar a mensagem!";
				$dados['alert']  = 'alert-error';
			}
		}

		$this->view('index', $dados);
	}

}