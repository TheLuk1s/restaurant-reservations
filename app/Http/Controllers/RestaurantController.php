<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return response()->json(['restaurants' => $restaurants]);
    }

    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return response()->json(['restaurant' => $restaurant]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $restaurant = Restaurant::create($request->all());

        return response()->json(['restaurant' => $restaurant], 201);
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $restaurant->update($request->all());

        return response()->json(['restaurant' => $restaurant]);
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return response()->json(null, 204);
    }
}
