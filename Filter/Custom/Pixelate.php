<?php

class Pixelate
{
    /*
     * Classe que cria o efeito de pixelate na imagem
     * fonte: http://www.tuxradar.com/practicalphp/11/2/24
     */

    protected $_image; //faz referencia a imagem

    public function addPixelate($_image)
    {
        $imagex = imagesx($_image);
        $imagey = imagesy($_image);
        $blocksize = 12;

        for ($x = 0; $x < $imagex; $x += $blocksize)
        {
            for ($y = 0; $y < $imagey; $y += $blocksize)
            {
                $rgb = imagecolorat($_image, $x, $y);
                imagefilledrectangle($_image, $x, $y, $x + $blocksize - 1, $y + $blocksize - 1, $rgb);
            }
        }

        return $_image;
    }
}
?>