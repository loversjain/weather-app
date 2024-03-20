<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Http\Resources\Admin\EventResource;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

/**
 * Class AdminController
 *
 * Controller class for managing events in the admin panel.
 *
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller
{
    /**
     * Display a listing of the events.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse Returns JSON response with paginated event data.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $events = Event::paginate($perPage);
            return EventResource::collection($events);
        } catch (\Exception $e) {
            Log::error('Failed to fetch events: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch events', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created event.
     *
     * @param StoreEventRequest $request The incoming store event request.
     * @return \Illuminate\Http\JsonResponse Returns JSON response with created event data.
     */
    public function store(StoreEventRequest $request)
    {
        try {
            $event = Event::createEvent($request->all());
            return response()
                    ->json(['message' => 'Event created successfully', 'data' => new EventResource($event)], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create event: ' , ["error" => $e->getMessage()] );
            return response()->json(['message' => 'Failed to create event', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified event.
     *
     * @param UpdateEventRequest $request The incoming update event request.
     * @param string $id The ID of the event to update.
     * @return \Illuminate\Http\JsonResponse Returns JSON response with updated event data.
     */
    public function update(UpdateEventRequest $request, int $id)
    {
        try {
            $event = Event::findOrFail($id);
            if (!$event) {
                return response()->json(['message' => 'Event not found'], 404);
            }
            $validatedData = $request->validated();
            $event->update($validatedData);
            return response()
                    ->json(['message' => 'Event updated successfully', 'data' => new EventResource($event)], 200);

        } catch (\Exception $e) {
            Log::error('Failed to update event: ' , ["error" => $e->getMessage(), "trace"=>$e->getTraceAsString()] );
            return response()->json(['message' => 'Failed to update event'], 500);
        }
    }

    /**
     * Soft delete the specified event.
     *
     * @param SoftDeleteEventRequest $request The incoming soft delete event request.
     * @param string $id The ID of the event to soft delete.
     * @return \Illuminate\Http\JsonResponse Returns JSON response indicating successful soft deletion.
     */
    public function destroy(int $id)
    {
        try {
            // Find the event by its ID and soft delete it
            $event = Event::findOrFail($id);
            if(empty($event)) {
                return response()->json(['message' => 'Event not found']);
            }
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to soft delete event: ' , ["error" => $e->getMessage()] );
            return response()->json(['message' => 'Failed to delete event', 'error' => $e->getMessage()], 500);
        }
    }
}
