<?php
/**
 * @author Konrad Baron <konradbaron@gmail.com> http://kobatechnologies.com
 */

class ZipCode {
	
	private $zipcode_base_url = "http://www.zipcodeapi.com/rest/";
	private $send_url;
	private $api_key;
	private $format;
	private $formatValid = array('json','xml','csv');
	private $zipcode;
	private $zipcode2;
	private $units;
	private $unitsValid = array('km','mile','degrees','radians');
	private $distance;
	private $city;
	private $state;
	
	
    	public function __construct($api_key)
    	{
        	$this->api_key = $api_key;
		$this->zipcode_base_url .= $this->api_key.'/';
    	}
	
	private function check_zipcode($zipcode) {
		if(!preg_match('/^\d{5}$/', $zipcode))  throw new Exception('Zip code is not valid.');
		return true;
	}
	
	private function check_state($state) {
		if(strlen($state) > 2) throw new Exception('Must use abbreviated form of state');
		return true;
	}
	
	private function check_units($units) {
		if(!in_array($units,$this->unitsValid)) throw new Exception('Unit is not valid. Must be set as one of the following: '.implode(', ',$this->unitsValid).'');
		return true;
	}
	
	private function check_format($format) {
		if(!in_array($format,$this->formatValid)) throw new Exception('Format is not valid. Must be set as one of the following: '.implode(', ',$this->formatValid).'');
		return true;
	}
	
	private function check_distance($distance) {
		if(!is_numeric($distance)) throw new Exception('Distance is not valid.');
		return true;
	}
	
	private function prepare_string($string) {
		$string = strtolower($string);
		return $string;
	}
	
	
	/**
 	* URL format http://www.zipcodeapi.com/rest/<api_key>/distance.<format>/<zip_code1>/<zip_code2>/<units>
	* Returns 'distance' between the two provided zip codes in the format requested. Default set to json.
 	*/
	public function get_zipcode_distance($zipcode,$zipcode2,$units = 'mile',$format = 'json'){
		$format = $this->prepare_string($format);
		$units = $this->prepare_string($units);
		$this->check_zipcode($zipcode);
		$this->check_zipcode($zipcode2);
		$this->check_units($units);
		$this->check_format($format);
		
		$this->send_url = $this->zipcode_base_url.'distance.'.$format.'/'.$zipcode.'/'.$zipcode2.'/'.$units;
		return $this->curl($this->send_url);
	}
	
	/**
 	* URL format http://www.zipcodeapi.com/rest/<api_key>/radius.<format>/<zip_code>/<distance>/<units>
	* Returns zipcodes within a given radius of proivided zipcode.
 	*/
	public function get_zipcodes_by_radius($zipcode,$distance,$units = 'mile',$format = 'json'){
		$format = $this->prepare_string($format);
		$units = $this->prepare_string($units);
		$this->check_zipcode($zipcode);
		$this->check_distance($distance);
		$this->check_units($units);
		$this->check_format($format);
		
		$this->send_url = $this->zipcode_base_url.'radius.'.$format.'/'.$zipcode.'/'.$distance.'/'.$units;
		return $this->curl($this->send_url);
	}
	
	/**
 	* URL format http://www.zipcodeapi.com/rest/<api_key>/info.<format>/<zip_code>/<units>
	* Returns city, state, latitude, longitude, and time zone information for a zip code. 
	* The JSON and XML responses will contain alternative acceptable city names for a location. CSV will not.
 	*/
	public function get_location_info_by_zipcode($zipcode,$units = 'degrees',$format = 'json'){
		$format = $this->prepare_string($format);
		$units = $this->prepare_string($units);
		$this->check_zipcode($zipcode);
		$this->check_units($units);
		$this->check_format($format);
		
		$this->send_url = $this->zipcode_base_url.'info.'.$format.'/'.$zipcode.'/'.$units;
		return $this->curl($this->send_url);
	}
	
	/**
 	* URL format http://www.zipcodeapi.com/rest/<api_key>/city-zips.<format>/<city>/<state>
	* Returns possible zip codes for a city.
 	*/
	public function get_zipcodes_by_location($city,$state,$format = 'json'){
		$format = $this->prepare_string($format);
		$city = $this->prepare_string($city);
		$state = $this->prepare_string($state);
		$this->check_format($format);
		$this->check_state($state);
		
		$this->send_url = $this->zipcode_base_url.'city-zips.'.$format.'/'.$city.'/'.$state;
		return $this->curl($this->send_url);
	}
	
	public function curl($send_url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $send_url);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$error = curl_error($ch);
			curl_close($ch);
			
			throw new Exception("Failed retrieving  '" . $this->send_url . "' because of '" . $error . "'.");
		}
		return $result;
	}
}
