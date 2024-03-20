<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Buyer\BuyerEventResource;
use App\Services\Buyer\EventSearchService;
use Illuminate\Support\Facades\Log;

/**
 * Class BuyerController
 *
 * Controller class for managing buyer-related operations.
 *
 * @package App\Http\Controllers\Buyer
 */
class BuyerController extends Controller
{
    protected $eventSearchService;

    /**
     * BuyerController constructor.
     *
     * @param EventSearchService $eventSearchService
     */
    public function __construct(EventSearchService $eventSearchService)
    {
        $this->eventSearchService = $eventSearchService;
    }

    /**
     * Search for events based on provided filters.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Routing\ResponseFactory
     * Returns JSON response with event data or error message.
     */
    public function index(Request $request)
    {
        try {
            // Extract filters from the request
            $filters = $request->only(['name', 'date', 'location']);

            // Perform event search based on filters
            $events = $this->eventSearchService->search($filters);

            // Return resource collection if events found
            return BuyerEventResource::collection($events);
        } catch (\Exception $e) {
            // Log and handle any unexpected errors
            Log::error('Failed to search events: ' ,["error" => $e->getMessage()]);
            return response()->json(['message' => 'Failed to search events', 'error' => $e->getMessage()], 500);
        }
    }
}
