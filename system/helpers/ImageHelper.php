<?php

/**
* 
*/
class ImageHelper
{

	private $_ALTURA_PADRAO;
	private $_pasta;
	

	/*
	 * Metodo construtor
	 * @param $pasta = Nome da pasta para upload
	 * @param $altura_padrao = Definir uma altura padrão para os arquivos
	 */
	function __construct($pasta, $altura_padrao = null)
	{

		$this->_pasta 			=  $pasta;
		$this->_ALTURA_PADRAO  =  $altura_padrao;

		if(is_null($this->_ALTURA_PADRAO)) $this->_ALTURA_PADRAO = 900;

		//VERIFICA SE A PASTA JÁ EXISTE, SE NÃO EXISTIR, ELA SERÁ CRIADA
		if (!is_dir($this->_pasta)) 
			@mkdir( $this->_pasta );
	}



	//GETTER AND SETTER
	public function __set($atrib, $value){  $this->$atrib = $value; }
	public function __get($atrib){  return $value; }
	
	


	/*
	 * Metodo ResizeByUrl
	 * Salva uma imagem a partir de um link
	 * @param $url = Url da imagem a ser salva
	 * @param $altura = Definir uma altura para a imagem atual, caso seja diferente da altura padrão
	 */
	public function ResizeByUrl($url, $altura = null)
	{

		//PEGA INFORMAÇÕES DA IMAGEM
		$file_info = getimagesize($url);

		//SE A ALTURA FOR MAIOR DO QUE A PADRÃO ELA SERA REDIMENCIONADA
		if($file_info[1] > $this->_ALTURA_PADRAO)
		{
		 	$altura = $this->_ALTURA_PADRAO;
		}


		//VERIFICA EXTENSÃO DA IMAGEM
		if ($file_info['mime'] == "image/jpeg")
			$img = imagecreatefromjpeg($url);
		else if ($file_info['mime'] == "image/gif")
			$img = imagecreatefromgif($url);
		else if ($file_info['mime'] == "image/png")
			$img = imagecreatefrompng($url);


		//SE NÃO PASSAR ALTURA ELE MANTÉM O TAMANHO ORIGINAL
		if (is_null($altura)) 
			$altura = $file_info[1];


		//PASSA AS MEDIDAS PARA A IMAGEM
		$x   = imagesx($img);
		$y   = imagesy($img);
		$largura = ($altura * $x)/$y;


		//CRIA A NOVA IMAGEM 
		$nova = imagecreatetruecolor($largura, $altura);
		imagealphablending( $nova, false );
		imagesavealpha( $nova, true );
		imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);


		//NOME DA IMAGEM
		$imgName = $this->Rename(end(explode("/", $url)));


		//LOCAL PARA SALVAR
		$local = $this->_pasta . $imgName;


		//SALVA NOVA IMAGEM
		if ($file_info['mime'] == "image/jpeg")
			imagejpeg($nova, $local, 100);
		else if ($file_info['mime'] == "image/png")
			imagepng($nova, $local, 9);

		//DESTROI ELEMENTOS
		imagedestroy($img);
		imagedestroy($nova);	
		
		return $imgName;
	}



	/*
	 * Metodo ResizeByUrl
	 * Salva uma imagem a partir de upload
	 * @param $imagem = ex: $_FILES['imagem']
	 * @param $altura = Definir uma altura para a imagem atual, caso seja diferente da altura padrão
	 */
	public function ResizeByUpload($imagem, $altura = null)
	{

		if($imagem['error'] == UPLOAD_ERR_OK)
		{

			//PEGA INFORMAÇÕES DA IMAGEM
			$file_info = getimagesize($imagem['tmp_name']);


			//SE A ALTURA FOR MAIOR DO QUE A PADRÃO ELA SERA REDIMENCIONADA
			if($file_info[1] > $this->_ALTURA_PADRAO)	$altura = $this->_ALTURA_PADRAO;


			//VERIFICA EXTENSÃO DA IMAGEM
			if ($file_info['mime'] == "image/jpeg")
				$img = imagecreatefromjpeg($imagem['tmp_name']);
			else if ($file_info['mime'] == "image/gif")
				$img = imagecreatefromgif($imagem['tmp_name']);
			else if ($file_info['mime'] == "image/png")
				$img = imagecreatefrompng($imagem['tmp_name']);


			//SE NÃO PASSAR ALTURA ELE MANTÉM O TAMANHO ORIGINAL
			if (is_null($altura)) 
				$altura = $file_info[1];


			//PASSA AS MEDIDAS PARA A IMAGEM
			$x   = imagesx($img);
			$y   = imagesy($img);
			$largura = ($altura * $x)/$y;


			//CRIA A NOVA IMAGEM 
			$nova = imagecreatetruecolor($largura, $altura);
			imagealphablending( $nova, false );
			imagesavealpha( $nova, true );
			imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);


			//NOME DA IMAGEM
			$imgName = $this->Rename(end(explode("/", $imagem['name'])));


			//LOCAL PARA SALVAR
			$local = $this->_pasta . $imgName;


			//SALVA NOVA IMAGEM
			if ($file_info['mime'] == "image/jpeg")
				imagejpeg($nova, $local, 100);
			else if ($file_info['mime'] == "image/png")
				imagepng($nova, $local, 9);


			//DESTROI ELEMENTOS
			imagedestroy($img);
			imagedestroy($nova);	
			
			return $imgName;
		}
		else
		{
			die('Error');
		}
	}



	/*
	 * Metodo Rename
	 * Remove acentos, espaços e caracteres especiais e cria um nome aleatório.
	 * @param $string = string a ser limpa
	 */
	public function Rename( $string ) 
	{
		// Converte todos os caracteres para minusculo
		$string = strtolower($string);

		// Remove os acentos
		$string = preg_replace('[á|à|ã|â|ä|Á|À|Ã|Â|Ä]', 'a', $string);
		$string = preg_replace('[é|è|ê|ë|É|È|Ê|Ë]',		'e', $string);
		$string = preg_replace('[í|ì|î|ï|Í|Ì|Î|Ï]', 	'i', $string);
		$string = preg_replace('[ó|ò|õ|ô|ö|Ó|Ò|Õ|Ô|Ö]', 'o', $string);
		$string = preg_replace('[ú|ù|û|ü|Ú|Ù|Û|Ü]', 	'u', $string);
		
		// Remove o cedilha e o ñ
		$string = preg_replace('[ç]', 'c', $string);
		$string = preg_replace('[ñ]', 'n', $string);
		
		// Substitui os espaços em brancos por underline
		$string = preg_replace('[ ]', '', $string);
		$string = preg_replace('[%|#|&]', '', $string);
		
		// Remove hifens duplos
		$string = preg_replace('[--]', '', $string);
		
		return md5(uniqid(time())).$string;	
	}
}