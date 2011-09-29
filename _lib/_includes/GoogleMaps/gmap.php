<?php
//usage example of staticgmap class
include_once("staticgmap.php");
$location = $_GET['location'];//name of the location
$markers = $_GET['markers'];//specify markers in url as &markers=dhaka,dhaka medical college,shahbag
if (empty($markers)) $markers = $location;
$markers = explode(",",$markers); 
$zoom = empty($_GET['zoom'])?"12":$_GET['zoom']; //specify zoom level as &zoom=15. use zoom level from 0 to 19
$format = empty($_GET['format'])?"GIF":$_GET['format']; //format of the output image. use it like &format=gif
$maptype = empty($_GET['maptype'])?"mobile":$_GET['maptype']; // &maptype=satellite
$size = empty($_GET['size'])?array(400,400):explode("x",$_GET['size']); //&size=500x300
$redirect = $_GET['redirect']; //if you specify &redirect=true - it will be redirected to the map url. else it will passthrough 

$gm = new StaticGMap();
$gm->location = $location;
$gm->zoom=$zoom;
$gm->format=$format;
$gm->maptype=$maptype;
$gm->setGeoCodingService("google");
foreach($markers as $marker){
	$gm->addMarker($marker,strtolower($marker[0]),"green");
}
$gm->setMapSize($size[0],$size[1]);
$mapurl = $gm->getMapUrl();
//echo $mapurl;
if($redirect)
header("Location: {$mapurl}");
else
{
	header('Content-Type: image/'.strtolower($gm->format));
	echo file_get_contents($mapurl);
	//echo $mapurl;
}
?>