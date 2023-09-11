<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WeatherController extends AbstractController
{
    private $httpClient;
    private $params;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $params)
    {
        $this->httpClient = $httpClient;
        $this->params = $params;
    }

    /**
     * @Route("/", name="app_weather")
     */
    public function index(Request $request)
    {
        $city = $request->query->get('city');
        $weatherData = null;
        $error = null;
        if ($city) {
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
                $weatherData = $response->toArray();
            } else {
                $error = 'Błąd podczas pobierania danych pogodowych.';
            }
        }

        return $this->render('weather/index.html.twig', [
            'city' => $city,
            'weatherData' => $weatherData,
            'error' => $error,
        ]);
    }
}
