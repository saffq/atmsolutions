<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OpenWeatherMapDataProvider implements WeatherDataProviderInterface
{
    private HttpClientInterface $httpClient;
    private ParameterBagInterface $params;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $params)
    {
        $this->httpClient = $httpClient;
        $this->params = $params;
    }

    public function getWeatherData(string $city): array
    {
        $apiKey = $this->params->get('app.api_key');
        $response = $this->httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'pl',
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        } else {
            return [];
        }
    }
}
