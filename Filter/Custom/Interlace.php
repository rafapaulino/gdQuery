<?php

class Interlace
{
    /*
     * Classe que adiciona o efeito Interlace na imagem
     * fonte: http://www.tuxradar.com/practicalphp/11/2/18
     */
    
    protected $_image; //faz referencia a imagem
    
    public function addInterlace($_image)
    {
        $imagex  = imagesx($_image);
        $imagey  = imagesy($_image);
        $black   = imagecolorallocate($_image, 0, 0, 0);

        // loop through all rows in the image

        for ($y = 0; $y < $imagey; ++$y)
        {
            // if it is even...
            if ($y % 2)
            {
                // loop through all pixels in this row
                for ($x = 0; $x < $imagex; ++$x)
                {
                    // set them to black
                    ImageSetPixel($_image, $x, $y, $black);
                }
            }
        }
        return $_image;
    }
}

?>