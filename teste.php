<?php
error_reporting("E_ALL");
ini_set('error_reporting', E_ALL);
 
include "GdQuery.php";
   
$gd = new GdQuery;
$gd->grabImage('image.jpg')
//filters
	->addFilter('desature')
	->addFilter('smooth','100')
//actions
    ->addAction('resize','300','300', true)
	->saveImage('nova','jpg');
	//->showImage();
