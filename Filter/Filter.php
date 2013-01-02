<?php


class Filter
{
    /*
     * Essa classe aplica uma série de filtros na imagem como transforamr em negativo, preto e branco, adicionar uma cor,
     * desfoque gaussiano, desfoque normal, mudar o contrast, etc...
     */

    protected $_image; //referencia a imagem para retorno no sharpen
    protected $_sharpen; //referencia a classe do sharpen
    //filtros da gd que nao recebem parametros
    protected static $_simpleFilters =   array('negative' => IMG_FILTER_NEGATE, 'desature' => IMG_FILTER_GRAYSCALE, 'edgeDetect' => IMG_FILTER_EDGEDETECT, 'emboss' => IMG_FILTER_EMBOSS, 'gaussianBlur' => IMG_FILTER_GAUSSIAN_BLUR, 'blur' => IMG_FILTER_SELECTIVE_BLUR, 'sktech' => IMG_FILTER_MEAN_REMOVAL);
    //filtros customizados recebem ou nao parametros
    protected static $_customFilters =   array('noise','scatter','pixelate','screen','interlace','sharpen');
    //filtros da gd que recebem parametros
    protected static $_advancedFilters = array('brightness' => IMG_FILTER_BRIGHTNESS, 'contrast' => IMG_FILTER_CONTRAST, 'colorize' => IMG_FILTER_COLORIZE, 'smooth' => IMG_FILTER_SMOOTH);

    /*
     * Metodo que aplica o filtro na imagem
     * retorna a imagem na memoria
     */
    public function addFilter($_image, $filter, $param1, $param2, $param3)
    {
        
        //se o filtro estiver no array de filtros simples
        if(array_key_exists($filter,self::$_simpleFilters))
        {
                imagefilter($_image, self::$_simpleFilters[$filter]);
                return $_image;
        }
        //verifico se o filtro e do array de customizados
        elseif(in_array($filter,self::$_customFilters))
        {
            //verifico que tipo de filtro e esse
            switch ($filter)
            {
                case "sharpen":
                  require_once "Custom/Sharpen.php"; //Efeito Sharpen
                  //arumando as propriedades para que nao ocorra nehum tipo de erro durante a execucao
                  $param1 = (($param1 > 200 || $param1 < 50)?50:$param1);
                  $param2 = (($param2 > 1 || $param2 < 0.5)?0.5:$param2);
                  $param3 = (($param3 > 5 || $param3 < 0)?0:$param3);

                  //instanciando a classe do Sharpen
                  $sharpen = new Sharpen;
                  $_image = $sharpen->addSharpen($_image, $param1, $param2, $param3);
                  return $_image;
                break;

                case "noise":
                  require_once "Custom/Noise.php"; //Efeito Noise
                  //instanciando a classe de noise
                  $noise = new Noise;
                  $_image = $noise->addNoise($_image);
                  return $_image;
                break;

                case "scatter":
                  require_once "Custom/Scatter.php"; //Efeito Scatter
                  //instanciando a classe de scatter
                  $scatter = new Scatter;
                  $_image = $scatter->addScatter($_image);
                  return $_image;
                break;

                case "pixelate":
                  require_once "Custom/Pixelate.php"; //Efeito Pixelate
                  //instanciando a classe de pixelate
                  $pixelate = new Pixelate;
                  $_image = $pixelate->addPixelate($_image);
                  return $_image;
                break;

                case "interlace":
                  require_once "Custom/Interlace.php"; //Efeito Interlace
                  //instanciando a classe de interlace
                  $interlace = new Interlace;
                  $_image = $interlace->addInterlace($_image);
                  return $_image;
                break;

                case "screen":
                  require_once "Custom/Screen.php"; //Efeito Screen
                  //instanciando a classe de Screen
                  $screen = new Screen;
                  $_image = $screen->addScreen($_image);
                  return $_image;
                break;
            }
        }
        //se o filtro estiver no array de filtros simples
        elseif(array_key_exists($filter,self::$_advancedFilters))
        {
            //verifico se o filtro recebe mais de um parametro
            if($filter == 'colorize')
            {
                imagefilter($_image, self::$_advancedFilters[$filter], $param1, $param2, $param3);
                return $_image;
            }

            //caso contrario
            else
            {
                imagefilter($_image, self::$_advancedFilters[$filter], $param1);
                return $_image;
            }
        }
        //se nao existir entao sera lancada uma excessao
        else
        {
          throw new Exception("Filtro inexistente - Absent filter");
          return false;
        }
    }


}

?>