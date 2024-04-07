<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Table;

class RestaurantTableController extends Controller
{
    public function index($restaurantId)
    {
        $tables = Table::where('restaurant_id', $restaurantId)->get();
        return response()->json(['tables' => $tables]);
    }

    public function store(Request $request, $restaurantId)
    {
        $request->validate([
            'capacity' => 'required|integer',
        ]);

        $table = new Table();
        $table->restaurant_id = $restaurantId;
        $table->capacity = $request->capacity;
        $table->save();

        return response()->json(['table' => $table], 201);
    }

    public function show($restaurantId, $tableId)
    {
        $table = Table::where('restaurant_id', $restaurantId)->findOrFail($tableId);
        return response()->json(['table' => $table]);
    }

    public function update(Request $request, $restaurantId, $tableId)
    {
        $request->validate([
            'capacity' => 'required|integer',
        ]);

        $table = Table::where('restaurant_id', $restaurantId)->findOrFail($tableId);
        $table->capacity = $request->capacity;
        $table->save();

        return response()->json(['table' => $table]);
    }

    public function destroy($restaurantId, $tableId)
    {
        $table = Table::where('restaurant_id', $restaurantId)->findOrFail($tableId);
        $table->delete();

        return response()->json(['message' => 'Table deleted successfully']);
    }
}
