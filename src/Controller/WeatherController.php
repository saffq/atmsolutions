<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherDataProviderInterface;

class WeatherController extends AbstractController
{
    public function __construct(private WeatherDataProviderInterface $dataProvider)
    {
    }

    public function index(Request $request)
    {
        $city = $request->query->get('city');
        $weatherData = null;
        $error = null;

        if ($city) {
            $weatherData = $this->dataProvider->getWeatherData($city);

            if (!$weatherData) {
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
