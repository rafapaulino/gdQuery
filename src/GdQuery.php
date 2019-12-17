<?php 

namespace GdQuery;

class GdQuery
{
    protected $image;
    protected $width;
    protected $height;

    public function __construct($image)
    {
        $this->setImage($image);
        $info = getimagesize($image);

        if (is_array($info) && count($info) > 2 && isset($info[0]) && isset($info[1]) ) {
            $this->setWidth($info[0]);
            $this->setHeight($info[1]);
        }        
    }

    

    /**
     * Get the value of width
     */ 
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of width
     *
     * @return  self
     */ 
    protected function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the value of height
     */ 
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of height
     *
     * @return  self
     */ 
    protected function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    protected function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}