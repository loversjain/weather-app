<?php

namespace App\Enums;

enum CacheTime:int
{
    /**
     * 1 hour
     */
    case WEATHER_ONE_HOUR = 3600;

    /**
     * 2 hour
     */
    case WEATHER_TWO_HOURS = 7200;
}