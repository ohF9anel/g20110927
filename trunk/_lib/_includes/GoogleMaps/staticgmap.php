<?php
/**
 * static google maps api which is a core part of orchid framework
 * @author Hasin Hayder - 30th Oct
 * @copyright LGPL
 */

class StaticGMap
{
	/**
	 * the name of the place
	 *
	 * @var string
	 */
	public $location;
	public $latitude;
	public $longitude;
	
	/**
	 * zoom level 0-19
	 *
	 * @var int
	 */
	public $zoom=12;
	
	/**
	 * array of location names to mark on the map. to update this variable use 
	 * addMarker() and removeMarker() function
	 *
	 * @var array
	 */
	public $markers=array();
	/**
	 * google map api key
	 *
	 * @var string
	 */
	public $key= "MAPS_API_KEY";
	
	/**
	 * map type, wither one of roadmap, satellite, terrain, hybrid and mobile
	 *
	 * @var string
	 */
	public $maptype="roadmap"; 
	
	/**
	 * image dimension. in a format of WIDTHxHEIGHT like (500x400).
	 *
	 * @var unknown_type
	 */
	public $size="400x400";
	
	/**
	 * image output format
	 *
	 * @var unknown_type
	 */
	public $format = "GIF";
	/**
	 * prefered geocoding service to convert location name to latitude and longitude.
	 * to set this parameter use setGeocodingService() function. 
	 *
	 * @var string
	 */
	public $gcs = "getYahooGeoCode";
	
	private $url="http://maps.google.com/staticmap?";
	
	function __construct($location="")
	{
		if(!empty($location))
		$this->setLocation($location);
	}
	
	/**
	 * This function finds the geocode of any given place using the geocoding service of yahoo
	 * @example getGeoCode("White House, Washington");
	 * @example getGeoCode("Machu Pichu");
	 * @example getGeoCode("Dhaka");
	 * @example getGeoCode("Hollywood");
	 *
	 * @param   string address
	 * @return  array with three key values, lat, lng and address
	 */
	function getYahooGeoCode($address)
	{
		$_url = 'http://api.local.yahoo.com/MapsService/V1/geocode';
		$_url .= sprintf('?appid=%s&location=%s',"phpclasses",rawurlencode($address));
		$_result = false;
		if($_result = file_get_contents($_url)) {
			preg_match('!<Latitude>(.*)</Latitude><Longitude>(.*)</Longitude>!U', $_result, $_match);
			$lng = $_match[2];
			$lat = $_match[1];
			return array("lat"=>$lat,"lng"=>$lng,"address"=>$address);
		}
		else
		return false;
	}
	
	/**
	 * This function finds the geocode of any given place using the geocoding service of google maps
	 * @example getGeoCode("White House, Washington");
	 * @example getGeoCode("Machu Pichu");
	 * @example getGeoCode("Dhaka");
	 * @example getGeoCode("Hollywood");
	 *
	 * @param   string address
	 * @return  array with three key values, lat, lng and address
	 */
	function getGoogleGeoCode($address)
	{
		$address = urlencode($address);
		$_url = "http://maps.google.com/maps/geo?q={$address}&output=json&key={$this->key}";
		//echo $_url;
		if($data = json_decode(file_get_contents($_url))) {
			$address= $data->Placemark[0]->address;

			$lat = $data->Placemark[0]->Point->coordinates[1];
			$lng = $data->Placemark[0]->Point->coordinates[0];
			return array("lat"=>$lat,"lng"=>$lng,"address"=>$address);
		}
		else
		return false;
	}
	
	
	/**
	 * generate url of the static map based on given parameters
	 *
	 * @param string $location optional. you can set it as "nocenter" for automatic alignment of the static map
	 * managed by google itself. 
	 * @return string url of the static map
	 */
	public function getMapUrl($location="")
	{
		$gcs = $this->gcs;
		if(!empty($location)) $this->location=$location;
		if (empty($this->location)) return false;
		
		// now start calculating the lat-lon and construct the map url
		if($this->location!="nocenter")
		$center = $this->$gcs($this->location);
		$tempMarkers = array();
		$markers = "";
		foreach ($this->markers as $key=>$marker)
		{
			//we can use a cache here
			//echo $marker[0]."<br/>";
			$geom = $this->$gcs($marker[0]);
			//print_r($geom);
			$tempMarkers[] = "{$geom['lat']},{$geom['lng']},{$marker[2]}{$marker[1]}{$marker[3]}";
			//$markers .= "{$geom[0]},{$geom[1]},{$marker[2]}{$marker[3]}";
		}
		$markers .= join("%7C",$tempMarkers);
		

		$mapurl = $this->url;
		if($this->location!="nocenter")
		$mapurl .= "center={$center['lat']},{$center['lng']}";
		$mapurl .= "&markers={$markers}";
		
		if($this->zoom!="nozoom")
		$mapurl .= "&zoom={$this->zoom}";
		$mapurl .= "&size={$this->size}";
		$mapurl .= "&maptype={$this->maptype}";
		$mapurl .= "&format={$this->format}";
		$mapurl .= "&key={$this->key}";
		
		return $mapurl;
		
	}
	
	/**
	 * add a place in the markers array. these places will be marked in the map image
	 *
	 * @param string $location name of the location
	 * @param string $highlight one character to denote the marker
	 * @param string $color color of the marker which is either one of black, brown, green, purple, yellow, blue, gray, orange, red, white
	 * @param string $size size of marker. specify either one of tiny, mid, small
	 */
	public function addMarker($location, $highlight = "", $color="blue", $size="mid")
	{
		$this->markers[md5($location)]=array($location,$color,$size,strtolower($highlight));
	}
	
	/**
	 * remove a location from the markers array
	 *
	 * @param string $location
	 */
	public function removeMarker($location)
	{
		unset($this->markers[md5($location)]);
	}
	
	/**
	 * set map size
	 *
	 * @param int $width
	 * @param int $height
	 */
	public function setMapSize($width=400, $height=400)
	{
		$this->size = "{$width}x{$height}";
	}
	
	/**
	 * set output format of the map image
	 *
	 * @param string $format image format. either one of gif, png or jpeg
	 */ 
	public function setMapFormat($format="GIF")
	{
		$this->format = strtoupper($format);
	}
	
	/**
	 * set your preferred geocoding service to use. 
	 *
	 * @param string $gcs either yahoo or google
	 */
	public function setGeoCodingService($gcs = "yahoo")
	{
		if(strtolower($gcs)=="yahoo")
		$this->gcs="getYahooGeoCode";
		else	
		$this->gcs="getGoogleGeoCode";	
	}
	
}
?>