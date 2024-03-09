<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Models\Event; // Import Event model
use Carbon\Carbon;

/**
 * Class EventController
 *
 * Controller class for managing events.
 *
 * @package App\Http\Controllers
 */
class EventController extends Controller
{
    protected $weatherService;

    /**
     * EventController constructor.
     *
     * @param WeatherService $weatherService
     */
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Get the weather data for a specific city and date.
     *
     * @param Request $request
     * @return mixed
     */
    public function getWeather(Request $request)
    {
        // Securely retrieve city and date from the request
        $city = $request->input('city'); 
        $date = Carbon::parse($request->input('date'))->format('Y-m-d'); 
        
        // Securely instantiate WeatherService
        $weatherService = app(WeatherService::class);
        
        // Fetch weather data securely
        $weatherData = $weatherService->getWeatherByCity($city, $date);
        
        return $weatherData;
        // Handle weather data...
    }
}
