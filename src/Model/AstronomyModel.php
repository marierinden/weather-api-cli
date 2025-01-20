<?php

namespace Marie\WeatherApp\Model;

use Marie\WeatherApp\Interface\ModelInterface;

readonly class AstronomyModel implements ModelInterface
{
    public function __construct(
        private array $data
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getLocation(): array
    {
        return $this->data['location'];
    }

    public function getAstronomy(): array
    {
        return $this->data['astronomy'];
    }
}