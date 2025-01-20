<?php

namespace Marie\WeatherApp\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
     * @throws GuzzleException
     */
    public function request(array $params = []): ResponseInterface
    {
        $params = array_merge($params, ['key' => $this->apiKey]);
        $endpoint = sprintf('%s%s',self::BASE_URL, '/astronomy.json');
        return $this->client->get($endpoint, [
            'query' => $params,
        ]);
    }

    /**
     * @param string $city
     * @return AstronomyModel
     * @throws GuzzleException
     */
    public function getAstronomyByCity(string $city): AstronomyModel
    {
        $response = $this->request(['q' => $city]);
        $astronomyData = json_decode($response->getBody()->getContents(), true);
        return new AstronomyModel($astronomyData);
    }
}
