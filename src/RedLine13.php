<?php
namespace KonradBaron\RedLine13;

use GuzzleHttp\Client as HttpClient;

class RedLine13
{
	private $zipcodeBaseUrl = "http://www.zipcodeapi.com/rest/";
	private $formatValid = array('json','xml','csv');
	private $unitsValid = array('km','mile','degrees','radians');
	private $client;

	public function __construct($apiKey)
	{
		$this->zipcodeBaseUrl .= $apiKey.'/';
		$this->client = new HttpClient();
	}

	private function checkZipcode($zipcode)
	{
		if(!preg_match('/^\d{5}$/', $zipcode))  throw new \Exception('Zip code is not valid.');
		return true;
	}

	private function checkState($state)
	{
		if(strlen($state) > 2) throw new \Exception('Must use abbreviated form of state');
		return true;
	}

	private function checkUnits($units)
	{
		if(!in_array($units,$this->unitsValid)) throw new \Exception('Unit is not valid. Must be set as one of the following: '.implode(', ',$this->unitsValid).'');
		return true;
	}

	private function checkFormat($format)
	{
		if(!in_array($format,$this->formatValid)) throw new \Exception('Format is not valid. Must be set as one of the following: '.implode(', ',$this->formatValid).'');
		return true;
	}

	private function checkDistance($distance)
	{
		if(!is_numeric($distance)) throw new \Exception('Distance is not valid.');
		return true;
	}

	private function prepareString($string)
	{
		$string = strtolower($string);
		return $string;
	}

	protected function request($url)
	{
		$res = $this->client->request('GET', $url);
		return $res->getBody();
	}

	/**
	 * URL format http://www.zipcodeapi.com/rest/<api_key>/distance.<format>/<zip_code1>/<zip_code2>/<units>
	 * Returns 'distance' between the two provided zip codes in the format requested. Default set to json.
	 */
	public function getZipcodeDistance($zipcode,$zipcode2,$units = 'mile',$format = 'json')
	{
		$format = $this->prepareString($format);
		$units = $this->prepareString($units);
		$this->checkZipcode($zipcode);
		$this->checkZipcode($zipcode2);
		$this->checkUnits($units);
		$this->checkFormat($format);

		$sendUrl = $this->zipcodeBaseUrl.'distance.'.$format.'/'.$zipcode.'/'.$zipcode2.'/'.$units;
		return $this->request($sendUrl);
	}

	/**
	 * URL format http://www.zipcodeapi.com/rest/<api_key>/radius.<format>/<zip_code>/<distance>/<units>
	 * Returns zipcodes within a given radius of proivided zipcode.
	 */
	public function getZipcodesByRadius($zipcode,$distance,$units = 'mile',$format = 'json')
	{
		$format = $this->prepareString($format);
		$units = $this->prepareString($units);
		$this->checkZipcode($zipcode);
		$this->checkDistance($distance);
		$this->checkUnits($units);
		$this->checkFormat($format);

		$sendUrl = $this->zipcodeBaseUrl.'radius.'.$format.'/'.$zipcode.'/'.$distance.'/'.$units;
		return $this->request($sendUrl);
	}

	/**
	 * URL format http://www.zipcodeapi.com/rest/<api_key>/info.<format>/<zip_code>/<units>
	 * Returns city, state, latitude, longitude, and time zone information for a zip code.
	 * The JSON and XML responses will contain alternative acceptable city names for a location. CSV will not.
	 */
	public function getLocationInfoByZipcode($zipcode,$units = 'degrees',$format = 'json')
	{
		$format = $this->prepareString($format);
		$units = $this->prepareString($units);
		$this->checkZipcode($zipcode);
		$this->checkUnits($units);
		$this->checkFormat($format);

		$sendUrl = $this->zipcodeBaseUrl.'info.'.$format.'/'.$zipcode.'/'.$units;
		return $this->request($sendUrl);
	}

	/**
	 * URL format http://www.zipcodeapi.com/rest/<api_key>/city-zips.<format>/<city>/<state>
	 * Returns possible zip codes for a city.
	 */
	public function getZipcodesByLocation($city,$state,$format = 'json')
	{
		$format = $this->prepareString($format);
		$city = $this->prepareString($city);
		$state = $this->prepareString($state);
		$this->checkFormat($format);
		$this->checkState($state);

		$sendUrl = $this->zipcodeBaseUrl.'city-zips.'.$format.'/'.$city.'/'.$state;
		return $this->request($sendUrl);
	}
}