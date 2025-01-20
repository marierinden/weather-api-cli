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

$timezone = new DateTimeZone('Europe/Oslo');
$now = new DateTime('now', $timezone);
$sunsetTime = Datetime::createFromFormat('H:i A', $sunset, $timezone);

if ($now > $sunsetTime) {
    $sunsetTime->modify('+1 day');
}

$timeUntilSunset = $now->diff($sunsetTime);

$output = sprintf(
    'In %s the sun sets at %s and rises at %s. The current time is %s. Time until sunset: %s hours and %s minutes.',
    $city,
    $sunset,
    $sunrise,
    $now->format('H:i'),
    $timeUntilSunset->h,
    $timeUntilSunset->i
);

var_dump($output);