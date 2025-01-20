<?php

namespace Marie\WeatherApp\Interface;

use Psr\Http\Message\ResponseInterface;

interface ServiceInterface
{
    public function request(array $params = []): ResponseInterface;
}
