<?php

namespace App\Dto;

use Illuminate\Http\Request;

class RestaurantDto
{
    public function __construct(
        public readonly string $name,
    ) {}

    public static function fromRequest(Request $request): RestaurantDto
    {
        return new self(
            $request->name
        );
    }
}