<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminV1Request;
use App\Http\Requests\Admin\StoreEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminV1Controller extends Controller
{
    public function store(AdminV1Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validatedData = $request->validated();
            return (new AdminController)->store(new StoreEventRequest($validatedData));
        } catch (\Exception $e) {
            Log::error('Failed to store event: ' , ["error=>"=>$e->getMessage()]);
            return response()->json(['message' => 'Failed to store event', 'error' => $e->getMessage()], 500);
        }
    }
}
