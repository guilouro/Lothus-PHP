<?php

class TemplateParser extends System
{
    private $output;
 
    //construtor faz a carga do template
    function TemplateParser( $templateFile = 'default.phtml' )
    {
        (file_exists($templateFile)) ? $this->output = $this->parseFile($templateFile) : die('Erro: Arquivo '.$templateFile.' não encontrado');
    }
 
    //faz a substituição
    function parseTemplate($tags = array())
    {
        if(count($tags) > 0)
        {
            foreach($tags as $tag =>$data)
            {
                $data  =  (file_exists($data)) ? $this->parseFile($data) : $data;
                $this->output  =  str_replace('{'.$tag.'}',$data, $this->output);
            }
        }
        else {
            die('Erro: não encontramos o arquivo ou texto');
        }
    }
 
    //Enquanto o buffer de saída estiver ativo, não é enviada a saída do script
    function parseFile($file)
    {
        //Ativar o buffer de saída.
        ob_start();
        include($file);
        //O conteúdo deste buffer interno é copiado na variável $content
        $content  =  ob_get_contents();
        //descartar o conteúdo do buffer.
        ob_end_clean();
        return $content;
    }
 
    //Exibe o tempalte
    function display()
    {
        return $this->output;
    }
}