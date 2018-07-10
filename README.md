# PHP-ZipCode-API-RedLine13

PHP-ZipCode-API-RedLine13 is a PHP class for [ZipCodeApi.com by RedLine13](http://www.zipcodeapi.com).
You must first register an account to receive an API key. The API allows a free subscription, but limits the API usage to 250 requests per hour for that level.

The API currently returns your data in three formats; JSON, CSV and XML. This class has set JSON as the default return format.

## Requirements
This library requires the use of Composer.
```
composer require konradbaron/fantasydata:dev-master
```

##Example Usage

###Instantiate the class
```php
  use KonradBaron\RedLine13\RedLine13;
  $zipCode = new RedLine13(YOUR_API_KEY);
```
    
###Distance between two zip codes
```php
  echo $zipCode->getZipcodeDistance(ZIP_CODE, ZIP_CODE2);
```    
    
###Find all zip codes within a given radius of a zip code
```php
  echo $zipCode->getZipcodesByRadius(ZIP_CODE,RADIUS_DISTANCE);
```     
    
###Find city, state, latitude, longitude, and time zone information for a zip code
```php
  echo $zipCode->getLocationInfoByZipcode(ZIP_CODE);
```     
    
###Find out possible zip codes for a city
```php
  echo $zipCode->getZipcodesByLocation(CITY, STATE);
```     
