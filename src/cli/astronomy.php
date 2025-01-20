<?php

use GuzzleHttp\Client;
use Marie\WeatherApp\Service\AstronomyService;

require_once __DIR__ . '/../../vendor/autoload.php';

$client = new Client();
$astronomyService = new AstronomyService($client);
$astronomyDataInOslo = $astronomyService->getAstronomyByCity('oslo');

var_dump($astronomyDataInOslo);