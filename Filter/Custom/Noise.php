<?php

class Noise
{
    /*
     * Classe que cria o efeito de noise na imagem
     * fonte: http://www.tuxradar.com/practicalphp/11/2/22
     */

    protected $_image;  //faz referencia a imagem
    protected $_width;  //faz referencia a largura da imagem
    protected $_height; //faz referencia a altura da imagem
    
    public function addNoise($_image)
    {
         //pegando largura e altura da imagem
         $_width =  imagesx($_image);
         $_height = imagesy($_image);

         for ($x = 0; $x < $_width; ++$x)
         {
             for ($y = 0; $y < $_height; ++$y)
             {
                 if (rand(0,1))
                 {
                     $rgb = imagecolorat($_image, $x, $y);
                     $red = ($rgb >> 16) & 0xFF;
                     $green = ($rgb >> 8) & 0xFF;
                     $blue = $rgb & 0xFF;
                     $modifier = rand(-20,20);
                     $red += $modifier;
                     $green += $modifier;
                     $blue += $modifier;

                       if ($red > 255) $red = 255;
                       if ($green > 255) $green = 255;
                       if ($blue > 255) $blue = 255;
                       if ($red < 0) $red = 0;
                       if ($green < 0) $green = 0;
                       if ($blue < 0) $blue = 0;

                       $newcol = imagecolorallocate($_image, $red, $green, $blue);
                       imagesetpixel($_image, $x, $y, $newcol);
                 }
             }
         }
        return $_image;
    }
}
?>