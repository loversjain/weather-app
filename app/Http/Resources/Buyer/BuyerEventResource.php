<?php

namespace App\Http\Resources\Buyer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\WeatherService; 

class BuyerEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'temperature' => $this->getTemperature(),
        ];
    }

    /**
     * Get the temperature using the WeatherService.
     *
     * @return float|null
     */
    protected function getTemperature(): ?float
    {
        $weatherService = app(\App\Services\WeatherService::class);
        // Call the method from the WeatherService to get the temperature
        return $weatherService->getWeatherByCity($this->location, $this->date);
    }
}
