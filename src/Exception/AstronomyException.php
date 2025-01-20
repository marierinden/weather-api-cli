<?php

namespace Marie\WeatherApp\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

class AstronomyException extends Exception
{
    public function __construct(
        string $message,
        private readonly ?ResponseInterface $response = null,
    ) {
        parent::__construct($message);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
