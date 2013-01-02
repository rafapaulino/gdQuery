<?php

class Scatter
{
       /*
        * Classe que cria o efeito de Scarter na imagem
        * fonte: http://www.tuxradar.com/practicalphp/11/2/23
        */

       protected $_image; //faz referencia a imagem
       
       public function addScatter($_image)
       {
           $imagex = imagesx($_image);
           $imagey = imagesy($_image);

            for ($x = 0; $x < $imagex; ++$x)
            {
                for ($y = 0; $y < $imagey; ++$y)
                {
                    $distx = rand(-4, 4);
                    $disty = rand(-4, 4);

                    if ($x + $distx >= $imagex) continue;
                    if ($x + $distx < 0) continue;
                    if ($y + $disty >= $imagey) continue;
                    if ($y + $disty < 0) continue;

                    $oldcol = imagecolorat($_image, $x, $y);
                    $newcol = imagecolorat($_image, $x + $distx, $y + $disty);

                    imagesetpixel($_image, $x, $y, $newcol);
                    imagesetpixel($_image, $x + $distx, $y + $disty, $oldcol);
                }
            }

            return $_image;
       }

}

?>