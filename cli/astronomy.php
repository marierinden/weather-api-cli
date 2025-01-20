<?php

use GuzzleHttp\Client;
use Marie\WeatherApp\Exception\AstronomyException;
use Marie\WeatherApp\Service\AstronomyService;

require_once __DIR__ . '/../vendor/autoload.php';

$dotEnvPath = __DIR__ . '/../.env.local';
$env = parse_ini_file($dotEnvPath);
$apiKey = $env['WEATHER_API_KEY'];

$options = getopt('', ['city:']);

$client = new Client();
$astronomyService = new AstronomyService($client, $apiKey);

try {
    echo $astronomyService->getConsoleOutput($options['city']);
} catch (AstronomyException $exception) {
    if ($exception->getResponse()) {
        $responseBody = json_decode($exception->getResponse()->getBody()->getContents(), true);

        echo sprintf(
            'Received code %d and message %s from API',
            $responseBody['error']['code'],
            $responseBody['error']['message']
        );
    }
}