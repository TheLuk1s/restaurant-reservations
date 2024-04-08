<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return response()->json($restaurants);
    }

    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return response()->json($restaurant);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Restaurant::$validationRules);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $restaurant = Restaurant::create($request->all());

        return response()->json($restaurant, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Restaurant::$validationRules);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update($request->all());

        return response()->json($restaurant, 200);
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return response()->json(null, 204);
    }
}
