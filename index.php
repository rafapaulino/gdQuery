<?php
error_reporting("E_ALL");
ini_set('error_reporting', E_ALL);
 
include "GdQuery.php";
   
$gd = new GdQuery;
$gd->grabImage('test.jpg')
//filters
	->addFilter('negative')
	->addFilter('desature')
	->addFilter('edgeDetect')
	->addFilter('emboss')
	->addFilter('gaussianBlur')
	->addFilter('blur')
	->addFilter('sktech')
	->addFilter('noise')
	->addFilter('scatter')
	->addFilter('pixelate')
	->addFilter('screen')
	->addFilter('interlace')
	->addFilter('sharpen','200','0.8','3')
	->addFilter('brightness','80')
	->addFilter('brightness','-80')
	->addFilter('contrast','80')
	->addFilter('contrast','-80')
	->addFilter('colorize','0','0','255')
	->addFilter('smooth','100')
//actions
	->addAction('rotate','80')
	->addAction('crop','500','450','40','100')
	->addAction('resize','700','150')
	->addAction('resize',334,140)
	->addAction('crop','334','140','0','0')
	->addAction('waterMark','logo.gif','right','bottom','20')
	->addAction('waterMark','logo.png','left','bottom','40')
	->saveImage('test2','jpg')
	->showImage();
?>