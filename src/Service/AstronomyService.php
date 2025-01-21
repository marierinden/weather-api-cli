<?php

namespace Marie\WeatherApp\Service;

use DateMalformedStringException;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Marie\WeatherApp\Exception\AstronomyException;
use Marie\WeatherApp\Interface\ServiceInterface;
use Marie\WeatherApp\Model\AstronomyModel;
use Psr\Http\Message\ResponseInterface;

class AstronomyService implements ServiceInterface
{
    private const string BASE_URL = 'https://api.weatherapi.com/v1';

    public function __construct(
        private readonly Client $client,
        private readonly string $apiKey
    ) {
    }

    /**
     * @param array $params
     * @return ResponseInterface
     * @throws AstronomyException
     */
    public function request(array $params = []): ResponseInterface
    {
        $params = array_merge($params, ['key' => $this->apiKey]);
        $endpoint = sprintf('%s%s',self::BASE_URL, '/astronomy.json');

        try {
            return $this->client->get($endpoint, [
                'query' => $params,
            ]);
        } catch (GuzzleException $exception) {
            if ($exception instanceof RequestException) {
                throw new AstronomyException($exception->getMessage(), $exception->getResponse());
            }

            throw new AstronomyException($exception->getMessage());
        }

    }

    /**
     * @param string $city
     * @return AstronomyModel
     * @throws AstronomyException
     */
    public function getAstronomyByCity(string $city): AstronomyModel
    {
        $response = $this->request(['q' => $city]);
        $astronomyData = json_decode($response->getBody()->getContents(), true);
        return new AstronomyModel($astronomyData);
    }

    /**
     * @param string $city
     * @return string
     * @throws AstronomyException
     */
    public function getConsoleOutput(string $city): string
    {
        $astronomyData = $this->getAstronomyByCity($city);

        $location = $astronomyData->getLocation();
        $astronomy = $astronomyData->getAstronomy();

        $city = $location['name'];
        $sunrise = $astronomy['astro']['sunrise'];
        $sunset = $astronomy['astro']['sunset'];

        try {
            $timezone = new DateTimeZone('Europe/Oslo');
            $now = new DateTime('now', $timezone);
            $sunsetTime = Datetime::createFromFormat('H:i A', $sunset, $timezone);

            if ($now > $sunsetTime) {
                $sunsetTime->modify('+1 day');
            }
        } catch (DateMalformedStringException $exception) {
            throw new AstronomyException($exception->getMessage());
        }

        $timeUntilSunset = $now->diff($sunsetTime);

        return sprintf(
            'In %s the sun sets at %s and rises at %s. The current time is %s. Time until sunset: %s hours and %s minutes.',
            $city,
            $sunset,
            $sunrise,
            $now->format('H:i'),
            $timeUntilSunset->h,
            $timeUntilSunset->i
        );
    }
}