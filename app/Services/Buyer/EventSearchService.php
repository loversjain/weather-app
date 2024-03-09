<?php

namespace App\Services\Buyer;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

/**
 * Class EventSearchService
 *
 * Service responsible for searching events based on filters.
 *
 * @package App\Services\Buyer
 */
class EventSearchService
{
    /**
     * Search events based on provided filters.
     *
     * @param array $filters Array of filters for searching events.
     *                       Possible keys: 'name', 'date', 'location'.
     *
     * @return LengthAwarePaginator|null
     */
    public function search(array $filters): ?LengthAwarePaginator
    {
        // Validate input parameters
        if (!$this->validateFilters($filters)) {
            return null;
        }

        try {
            // Start building the query with the Event model
            $query = Event::query();

            // Loop through each filter and apply the corresponding condition to the query
            foreach ($filters as $key => $value) {
                match ($key) {
                    'name' => $query->where('name', 'like', "%$value%"),
                    'date' => $query->whereDate('date', $value),
                    'location' => $query->where('location', 'like', "%$value%"),
                    'description' => $query->where('description', 'like', "%$value%"),
                };
            }
            $query->orderByDesc('id');
            // Execute the query and paginate the results with 15 items per page
            return $query->paginate(15);
        } catch (\Exception $e) {
            // Log and handle any unexpected errors
            Log::error('Error occurred while searching for events: ' , ["error"=> $e->getMessage()]);
            return null;
        }
    }

    /**
     * Validate input filters.
     *
     * @param array $filters Array of filters for searching events.
     * @return bool Returns true if filters are valid, false otherwise.
     */
    protected function validateFilters(array $filters): bool
    {
        // Validate 'date' filter format
        if (isset($filters['date']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['date'])) {
            return false;
        }

        // Additional validation for other filters can be added here

        return true;
    }
}
