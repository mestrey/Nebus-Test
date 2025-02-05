<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * List all buildings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $buildings = Building::all();

        return response()->json([
            'status' => 'success',
            'data' => $buildings,
        ], 200);
    }
}
