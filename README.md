# PHP-ZipCode-API-RedLine13

PHP-ZipCode-API-RedLine13 is a PHP class for [ZipCodeApi.com by RedLine13](http://www.zipcodeapi.com).
You must first register an account to receive an API key. The API allows a free subscription, but limits the API usage to 250 requests per hour for that level.

The API currently returns your data in three formats; JSON, CSV and XML. This class has set JSON as the default return format.

##Example Usage

###Instantiate the class
    $zipCode = new ZipCode(YOUR_API_KEY);

###Distance between two zip codes
    $zipCode->get_zipcode_distance(ZIP_CODE, ZIP_CODE2);
    
###Find all zip codes within a given radius of a zip code
    $zipCode->get_zipcodes_by_radius(ZIP_CODE,RADIUS_DISTANCE);
    
###Find city, state, latitude, longitude, and time zone information for a zip code
    $zipCode->get_location_info_by_zipcode(ZIP_CODE);
    
###Find out possible zip codes for a city
    $zipCode->get_zipcodes_by_location(CITY, STATE);
