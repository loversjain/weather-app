<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Log;
use App\Enums\CacheTime;
/**
 * Class WeatherService
 * @package App\Services
 */
class WeatherService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * WeatherService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get weather forecast by city and date.
     *
     * @param string $city The name of the city.
     * @param string $date The date for which weather is required.
     * @return string|null Returns the average temperature for the specified city and date, or null if an error occurs.
     */
    public function getWeatherByCity(string $city, string $date): ?string
    {
        // Retrieve API key from environment variables
        $apiKey = env('WEATHER_API_KEY');

        // Get coordinates (latitude and longitude) for the provided city
        [$latitude, $longitude] = $this->getCoordinatesByCity($city);

        // If coordinates are not available, return "empty"
        if (empty($latitude) && $longitude) {
            return "empty";
        }
        try {
            // Generate cache key based on city and date
            $cacheKey = "weather:{$city}:{$date}";

            // Check if data exists in cache
            if (Cache::has($cacheKey)) {
                // If data exists in cache, retrieve and return it
                $averageTemperature = Cache::get($cacheKey);
                return $averageTemperature;
            }

            // Fetch data from the weather API
            $response = $this->client->get($this->getUrl($latitude, $longitude, $date, $apiKey));
            $data = json_decode($response->getBody(), true);

            // Log the API response
            Log::info('OpenWeatherMap API Response:', ['data' => $data]);

            // Extract hourly forecast data from the API response
            $hourlyData = isset($data['hourly']) ? $data['hourly'] : [];

            // Calculate average temperature from hourly forecast data
            if (!empty($hourlyData)) {
                $totalTemperature = 0;
                $totalCount = 0;
                foreach ($hourlyData as $hour) {
                    if (isset($hour['temp'])) {
                        $totalTemperature += $hour['temp'];
                        $totalCount++;
                    }
                }
                // Calculate average temperature
                $averageTemperature = $totalCount > 0 ? $totalTemperature / $totalCount : null;

                // Store data in cache with expiration time (3600 seconds = 1 hour)
                Cache::put($cacheKey, $averageTemperature, CacheTime::WEATHER_ONE_HOUR->value);
                return $averageTemperature;
            } else {
                return "Hourly data is empty.";
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur during fetching weather data
            Log::error('Exception occurred while fetching weather data:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return null;
        }
    }

    /**
	 * Get coordinates (latitude and longitude) for a given city.
	 *
	 * @param string $city The name of the city.
	 * @return array|null Returns an array containing latitude and longitude of the city, or null if an error occurs.
	 */
	protected function getCoordinatesByCity(string $city): ?array
	{
	    // Check if coordinates exist in cache
	    $cacheKey = "city_coordinates_{$city}";
	    if (Cache::has($cacheKey)) {
	        return Cache::get($cacheKey);
	    }

	    // Construct the URL to fetch latitude and longitude data for the provided city
	    $url = env('LAT_AND_LONG_API_URL') . $city;

	    try {
	        // Fetch data from the API
	        $response = $this->client->get($url);
	        $results = json_decode($response->getBody(), true);

	        // If data is available, extract latitude and longitude
	        if (!empty($results)) {
	            $latitude = $results[0]['lat'];
	            $longitude = $results[0]['lon'];

	            // Store coordinates in cache with expiration time (e.g., 1 hour)
	            Cache::put($cacheKey, [$latitude, $longitude], CacheTime::WEATHER_ONE_HOUR->value);

	            return [$latitude, $longitude];
	        } else {
	            return null;
	        }
	    } catch (\Exception $e) {
	        // Log any exceptions that occur during fetching coordinates
	        Log::error("exception in " . __FUNCTION__, ["error" => $e->getMessage(), "trace" => $e->getTraceAsString()]);
	        return null;
	    }
	}

	protected function getUrl(float $latitude, float $longitude, string $date, string $apiKey): ?string
	{
		// Construct the API URL to fetch hourly forecast data for the specified city and date
        return  env('WEATHER_BASE_URL') . "?lat=" . $latitude . "&lon=" . $longitude . "&exclude=minutely,daily,alerts" . "&date=" . $date . "&appid=" . $apiKey;
	}
}
