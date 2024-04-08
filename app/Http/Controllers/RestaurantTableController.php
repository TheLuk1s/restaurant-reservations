<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use App\Models\Table;

class RestaurantTableController extends Controller
{
    public function index($restaurantId)
    {
        $tables = Table::where('restaurant_id', $restaurantId)->get();
        return response()->json($tables);
    }

    public function show($restaurantId, $tableId)
    {
        $table = Table::where('restaurant_id', $restaurantId)->findOrFail($tableId);
        return response()->json($table);
    }

    public function store(Request $request, $restaurantId)
    {
        $validator = Validator::make(
            array_merge(
                $request->all(),
                ['restaurant_id' => $restaurantId]
            ), 
            Table::$validationRules
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $table = Table::create([
            'restaurant_id' => $restaurantId,
            'capacity' => $request->capacity
        ]);

        return response()->json($table, 201);
    }

    public function update(Request $request, $restaurantId, $tableId)
    {
        $validator = Validator::make(
            array_merge(
                $request->all(),
                ['restaurant_id' => $restaurantId]
            ), 
            Table::$validationRules
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $table = Table::where('restaurant_id', $restaurantId)->findOrFail($tableId);
        $table->capacity = $request->capacity;
        $table->save();

        return response()->json($table);
    }

    public function destroy($restaurantId, $tableId)
    {
        $table = Table::where('restaurant_id', $restaurantId)->findOrFail($tableId);
        $table->delete();

        return response()->json(null, 204);
    }
}
