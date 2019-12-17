<?php 

namespace GdQuery;

class GdQuery
{
    protected $path;
    protected $image;
    protected $width;
    protected $height;
    protected $type;

    public function __construct($path)
    {
        $this->setPath($path);
        $info = getimagesize($path);

        if ( is_array($info) && count($info) > 2 && isset($info[0]) && isset($info[1]) && isset($info[2]) ) {
            
            $this->setWidth($info[0]);
            $this->setHeight($info[1]);
            $this->setType($info[2]);

            $this->setImage();
        }
        
        return $this;
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
    protected function setImage()
    {
        switch($this->getType())
        {
            case 'gif':
                $image = imagecreatefromgif($this->getPath());
            break;
            case 'jpg':
                $image = imagecreatefromjpeg($this->getPath());
            break;
            case 'png':
                $image = imagecreatefrompng($this->getPath());
                imagealphablending($image, false);
                imagesavealpha($image, true);
            break;
        }

        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    protected function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    protected function setType($type)
    {
        switch($type)
        {
            case 1:
                $type = 'gif';
            break;
            case 2:
            default:
                $type = 'jpg';
            break;
            case 3:
                $type = 'png';
            break;
        }

        $this->type = $type;

        return $this;
    }
}