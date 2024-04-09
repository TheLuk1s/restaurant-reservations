<?php

namespace App\Dto;

use Illuminate\Http\Request;

class ClientDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string|null $phone
    ) {}

    public static function fromRequest(Request $request): ClientDto
    {
        return new self(
            $request->name,
            $request->email,
            $request->phone
        );
    }
}