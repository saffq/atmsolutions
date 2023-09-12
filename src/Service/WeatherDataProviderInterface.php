<?php

namespace App\Service;

interface WeatherDataProviderInterface
{
    public function getWeatherData(string $city): array;
}