<?php

class Screen
{
    /*
     * Classe que adiciona o efeito Screen na imagem
     * fonte: http://www.tuxradar.com/practicalphp/11/2/19
     */
    
    protected $_image; //faz referencia a imagem
    
    public function addScreen($_image)
    {
        $imagex  = imagesx($_image);
        $imagey  = imagesy($_image);
        $black   = imagecolorallocate($_image, 0, 0, 0);

         for($x = 1; $x <= $imagex; $x += 2)
         {
             imageline($_image, $x, 0, $x, $imagey, $black);
         }

         for($y = 1; $y <= $imagey; $y += 2)
         {
              imageline($_image, 0, $y, $imagex, $y, $black);
         }

        return $_image;
    }
}

?>