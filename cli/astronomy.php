<?php

use GuzzleHttp\Client;
use Marie\WeatherApp\Service\AstronomyService;

require_once __DIR__ . '/../vendor/autoload.php';

$dotEnvPath = __DIR__ . '/../.env.local';
$env = parse_ini_file($dotEnvPath);
$apiKey = $env['WEATHER_API_KEY'];

$client = new Client();
$astronomyService = new AstronomyService($client, $apiKey);
$astronomyDataInOslo = $astronomyService->getAstronomyByCity('oslo');

$location = $astronomyDataInOslo->getLocation();
$astronomy = $astronomyDataInOslo->getAstronomy();

$city = $location['name'];
$sunrise = $astronomy['astro']['sunrise'];
$sunset = $astronomy['astro']['sunset'];

$output = sprintf('In %s the sun sets %s and rises %s', $city, $sunset, $sunrise);

var_dump($output);