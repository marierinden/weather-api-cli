<?php

namespace Marie\WeatherApp\Interface;

use Marie\WeatherApp\Model\AstronomyModel;

interface ModelInterface
{
    public function getData(): array;
}
