<?php

//pego a classe que faz o crop, rotate, e demais alteracoes
require_once 'Action/Action.php';
//pego a classe que aplica os filtros na imagem
require_once 'Filter/Filter.php';

class GdQuery
{

   protected $_pathImage; //imagem passada
   protected $_red         = 255; //cor vermelho
   protected $_blue        = 255; //cor azul
   protected $_green       = 255; //cor verde
   protected static $_quality     = 100; //qualidade de saida para formatos jpg
   protected static $_files       = array('gif','jpg','png'); //array com os tipos de imagens suportados
   protected static $_memoryLimit = 16388608; //16mb limite de memoria para poder manipular uma imagem
   protected $_imageInfo   = array(); //array com as informacoes da imagem
   protected $_memory; //memoria restante
   protected $_width; //largura da imagem
   protected $_height; //altura da imagem
   protected $_originalImage; //nome original da imagem
   protected $_type; //tipo de imagem
   public    $_image; //a imagem
   protected $_applyFilter; //faz referencia a classe que aplica os filtros na imagem
   protected $_actions; //faz referencia a classe que executa as acoes de rotacionar,cropar,etc...
   protected $_newName; //o novo nome da imagem


   /*
    * Usando o método mágico __get para retornar os valores das propriedades e métodos dessa classe
    */
    public function __get($property)
    {
       return $this->$property;
    }
   

   /*
    * Essa funcao apenas pega os dados da imagem, cria a imagem com a gd e retorna um array com os dados
    * Caso a imagem não possa ser criada pela gd entao sera retornada uma excessao
    */
   public function grabImage($_pathImage)
   {
       //pegando as informacoes da imagem
       $_imageInfo = getimagesize($_pathImage);

       /*
        * Verificando se não vai estourar a memória usando a GDLib
        * espaço que ocupa em memoria (formula: memoria = width * height * bits)
        * http://www.phpavancado.net/node/244
        */
        $this->_memory = $_imageInfo[0] * $_imageInfo[1] * $_imageInfo['bits'];

       if($this->_memory > self::$_memoryLimit)
       {
           throw new Exception("Memoria insuficiente para a criacao da imagem - Insufficient memory for image creation");
           return false;
       }

       /*
        * Caso contrario o procedimento continua
        */
       else
       {
           //largura
           $this->_width  = $_imageInfo[0];

           //altura
           $this->_height = $_imageInfo[1];

           //path da imagem
           $this->_originalImage = $_pathImage;


              //verificando o tipo de imagem
              switch($_imageInfo[2])
              {
                  case 1:
                    $this->_type  = "gif";
                    $this->_image = imagecreatefromgif($_pathImage);
                    break;
                  case 2:
                    $this->_type  = "jpg";
                    $this->_image = imagecreatefromjpeg($_pathImage);
                    break;
                  case 3:
                    $this->_type  = "png";
                    $this->_image = imagecreatefrompng($_pathImage);
                    imagealphablending($this->_image, false);
                    imagesavealpha($this->_image,true);
                    break;
                  default:
                    $this->_type  = null;
                    $this->_image = null;
                  break;
              }

             //verificando se a imagem tem suporte
             if(!in_array($this->_type,self::$_files))
             {
                 throw new Exception("Tipo de imagem nao suportado - Image type not supported");
                 return false;
             }

             else
             {
                 //retorna as informacoes sobre a imagem
                 return $this;
             }

       }
	   
   }

   /*
    * Chamando a classe que aplica os filtros na imagem
    */
   public function addFilter($filter, $param1 = 0, $param2 = 0, $param3 = 0)
   {
       $_applyFilter = new Filter;
       $this->_image = $_applyFilter->addFilter($this->_image, $filter, $param1, $param2, $param3);
       return $this;
   }

   /*
    * Chamando a classe que executa as acoes na imagem
    */
   public function addAction($action, $param1 = 0, $param2 = 0, $param3 = 0, $param4 = 0)
   {
         //chamando a classe das acoes
         $_actions = new Action;
         //verificando que tipo de acao será executada
         switch($action)
         {
             case "rotate":
                $this->_image = $_actions->Rotate($this->_image,$param1);
                break;
             case "crop":
                $this->_image = $_actions->Crop($this->_image,$this->_width,$this->_height,$param1,$param2,$param3,$param4);
                break;
             case "resize":
                $this->_image = $_actions->Resize($this->_image,$this->_width,$this->_height,$param1,$param2,$param3);
                break;
             case "waterMark":
                $this->_image = $_actions->WaterMark($this->_image,$param1,$param2,$param3,$param4);
                break;
            default:
                break;

         }

         //defino o novo tamanho da imagem
         $this->_width  = imagesx($this->_image);
         $this->_height = imagesy($this->_image);

         return $this;
   }

   /*
    * Mostrando a imagem
    */
   public function showImage()
   {
        
       //verificando se a imagem existe
       if(isset($this->_image))
       {
            //verificando o tipo de imagem
            switch($this->_type)
            {
                case "gif":
                  header('Content-type: image/gif');
                  imagegif($this->_image);
                  break;
                case "jpg":
                  header('Content-type: image/jpg');
                  imagejpeg($this->_image);
                  break;
                case "png":
                  header('Content-type: image/png');
                  imagepng($this->_image);
                  break;
            }

            return $this;
       }

       else
       {
          throw new Exception("A referencia a imagem nao foi encontrada - The reference image was not found");
          return false;
       }
   }
   /*
    * Salvando a imagem
    */
   public function saveImage($name = null,$extension = null)
   {
       if(isset($this->_image))
       {

            //verificando se o nome da imagem nao foi passado
            if($name == null || trim($name) == "")
            {
                //verificando o tipo de imagem
                switch($this->_type)
                {
                    case "gif":
                      imagegif($this->_image,$this->_originalImage);
                      break;
                    case "jpg":
                      imagejpeg($this->_image,$this->_originalImage,self::$_quality);
                      break;
                    case "png":
                      imagepng($this->_image,$this->_originalImage);
                      break;
                }
            }
            //caso contrario
            else
            {
                //verificando se a extensao da imagem nao foi passada
                if($extension == null || trim($extension) == "" || !in_array($extension, self::$_files))
                {
                    //crio o novo nome da imagem
                    $_newName = $name.'.'.$this->_type;
                    
                    //verifico o tipo de imagem
                    switch($this->_type)
                    {
                       case "gif":
                         imagegif($this->_image,$_newName);
                         break;
                       case "jpg":
                         imagejpeg($this->_image,$_newName,self::$_quality);
                         break;
                       case "png":
                         imagepng($this->_image,$_newName);
                         break;
                    }
                    
                }
                //se a extensao nao for nula ou em branco
                else
                {
                    //crio o novo nome da imagem
                    $_newName = $name.'.'.$extension;
                    
                    //verifico o tipo de imagem
                    switch($extension)
                    {
                       case "gif":
                         imagegif($this->_image,$_newName);
                         break;
                       case "jpg":
                         imagejpeg($this->_image,$_newName,self::$_quality);
                         break;
                       case "png":
                         imagepng($this->_image,$_newName);
                         break;
                    }
                }


            }

            return $this;

       }

       else
       {
          throw new Exception("A referencia a imagem nao foi encontrada - The reference image was not found");
          return false;
       }
   }

   /*
    * Destrutor da classe
    */
   public function  __destruct()
   {
      //destruindo a imagem
      imagedestroy($this->_image);
   }


}

?>