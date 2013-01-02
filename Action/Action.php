<?php

class Action
{
	
    protected $_image; //referencia a imagem
    protected $_newImage; //referencia a nova imagem
    protected $_angle;  //angulo de rotacao da imagem
    protected $_width; //largura da imagem
    protected $_height; //altura da imagem
    protected $_newWidth; //nova largura da imagem
    protected $_newHeight; //nova altura da imagem
    protected $_left; //coordenada de posicionamento para o crop
    protected $_top; //coordenada de posicionamento para o crop
    protected static $_x = array('left','center','right'); //coordenada horizontal do posicionamento da marca d agua
    protected static $_y = array('top','center','bottom'); //coordenada vertical do posicionamento da marca d agua
    protected $_transparency; //transparencia da marca d agua
    protected $_waterMark; //imagem que servira de marca d agua
    protected $_imageTemp; //imagem temporaria com a marca d agua
    protected $_imageInfo; //informacoes sobre a marca d agua
    protected $_widthM; //largura da marca d agua
    protected $_heightM; //altura da marca d agua

    //classe que rotaciona a imagem
    public function Rotate($_image, $degrees)
    {
        //pego o angulo de rotacao da imagem
        $_angle = (int) 360 - $degrees;

        //seto a transparencia da imagem
        $color = imagecolorallocatealpha($_image, 255, 255, 255, 127);

        //faco a rotacao da imagem
        $_image = imagerotate($_image, $_angle, $color, 1);

        //pega a transparencia
        imagecolortransparent($_image, $color);
        imageantialias($_image, true);

        return $_image;
      
    }


    //resize na imagem
    public function Resize($_image,$_width,$_height,$_newWidth,$_newHeight,$proporcional = true)
    {

        /*
         * caso a imagem seja redimensionada apenas por um parametro como altura ou largura
         * assim a mesma sera redimensionada proporcionalmente
         */
        if($_newWidth == 0 && $_newHeight == 0) {
            $_newWidth = $_width;
            $_newHeight = $_height;

        //redimensionando a imagem proporcionalmente com base na nova largura dada
        }else if($_newHeight == 0) {
            $percent = $_newWidth/$_width;
            $_newHeight = round($_height * $percent);

        //redimensionando a imagem proporcionalmente com base na nova altura dada
        }else if($_newWidth == 0) {
            $percent = $_newHeight/$_height;
            $_newWidth = round($_width * $percent);

        //caso a largura e altura sejam passadas
        }else{
            //caso o redimensionamento seja proporcional
            if($proporcional == 0 || $proporcional == true) {
                
                //o redimensionamento será proporcional ao lado que é menor
                if($_width > $_height) {
                   $percent = $_newHeight/$_height;
                   $_tempWidth = round($_width * $percent);
                        //porem as vezes o lado menor faz a imagem ficar menor que o novo tamanho
                        if($_tempWidth < $_newWidth) {
                            $percent = $_newWidth/$_width;
                            $_newHeight = round($_height * $percent);
                        }else{
                            $_newWidth = $_tempWidth;
                        }

                }else if($_height > $_width) {
                   $percent = $_newWidth/$_width;
                   $_tempHeight = round($_height * $percent);

                        if($_tempHeight < $_newHeight) {
                             $percent = $_newHeight/$_height;
                             $_newWidth = round($_width * $percent);
                        }else{
                            $_newHeight = $_tempHeight;
                        }
                
                //caso a largura e altura sejam iguais
                }else{
                    $_newHeight = $_newWidth = max($_newWidth,$_newHeight);
                }

            //caso o redimensionamento nao seja proporcional
            }else{
               $_newWidth  = $_newWidth;
               $_newHeight = $_newHeight;
            }
            
        }

        //redimensiona a imagem
        $_newImage = imagecreatetruecolor($_newWidth,$_newHeight);

        imagecopyresampled($_newImage,$_image,0,0,0,0,$_newWidth,$_newHeight,$_width,$_height);

        return $_newImage;
    }


    //crop na imagem
    public function Crop($_image,$_width,$_height,$_newWidth,$_newHeight,$_left,$_top)
    {
        //verificando se a largura do crop nao e maior que a largura inicial da imagem
        if(($_newWidth - $_left) > $_width)
        {
           throw new Exception("Nao sera possivel recortar a imagem, pois o valor de corte informado e maior que o tamanho atual da imagem - You can not cut the picture since the cut-off and more informed than the current size image ");
           return false;
        }

        //verificando se a altura do crop nao e maior que a altura inicial da imagem
        if(($_newHeight - $_top) > $_height)
        {
           throw new Exception("Nao sera possivel recortar a imagem, pois o valor de corte informado e maior que o tamanho atual da imagem - You can not cut the picture since the cut-off and more informed than the current size image ");
           return false;
        }
        
        //redimensiona a imagem
        $_newImage = imagecreatetruecolor($_newWidth,$_newHeight);

        imagecopy($_newImage,$_image,0,0,$_left,$_top,$_width,$_height);

        return $_newImage;
    }

    //marca d agua
    public function WaterMark($_image,$_waterMark,$_x,$_y,$_transparency)
    {
        
        //pegando as informacoes da marca d agua
        $_imageInfo = getimagesize($_waterMark);
        
              //verificando o tipo de imagem
              switch($_imageInfo[2])
              {
                  case 1:
                    $_newImage = imagecreatefromgif($_waterMark);
                    break;
                  case 2:
                    $_newImage = imagecreatefromjpeg($_waterMark);
                    break;
                  case 3:
                    $_newImage = imagecreatefrompng($_waterMark);
                    imagealphablending($_newImage,false);
                    imagesavealpha($_newImage,true);
                    imageantialias($_newImage,true);


                        $transparencyIndex = imagecolortransparent($_newImage);
                        if ($transparencyIndex >= 0)
                        {
                            $transparencyColor    = imagecolorsforindex($_newImage, $transparencyIndex);
                        }
                        
                        $transparencyIndex    = $color = imagecolorallocatealpha($_newImage, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue'], 127);
                        imagefill($_newImage, 0, 0, $transparencyIndex);
                        imagecolortransparent($_newImage, $transparencyIndex);

                    break;
                  default:
                    throw new Exception("Nao sera possivel criar a marca d agua, pois o tipo de imagem da mesma nao e suportado - You can not create the watermark, since the image type and the same not supported ");
                    return false;
                  break;
              }

        //pegando o tamanho da imagem
        $_width  = imagesx($_image);
        $_height = imagesy($_image);

        //pegando o tamanho da marca d agua
        $_widthM  = $_imageInfo[0];
        $_heightM = $_imageInfo[1];

        //verificando se a marca d agua e maior que a imagem
        if($_width < $_widthM || $_height < $_heightM)
        {
            throw new Exception("Nao sera possivel criar a marca d agua, pois a marca d agua e maior que a imagem de destino - You can not create the watermark, because the watermarks and larger than the destination image ");
            return false;
        }

        else
        {

              if(!in_array($_x,self::$_x) || !in_array($_y,self::$_y))
              {
                return false;
              }
              else
              {
                     //definindo x
                     switch($_x)
                     {
                          case "left":
                             $_x = 5;
                          break;
                          case "center":
                             $_x = (int)($_width / 2) - ($_widthM / 2) - 5;
                          break;
                          case "right":
                             $_x = (int) ($_width - $_widthM) - 5;
                          break;
                     }

       
                     //definindo y
                     switch($_y)
                     {
                          case "top":
                             $_y = 5;
                          break;
                          case "center":
                             $_y = (int)($_height / 2) - ($_heightM / 2) - 5;
                          break;
                          case "bottom":
                             $_y = (int) ($_height - $_heightM) - 5;
                          break;
                     }

                   imagecopymerge($_image, $_newImage, $_x, $_y, 0, 0, $_widthM, $_heightM, $_transparency);
                   return $_image;

               }


         }


      }
	

}

?>