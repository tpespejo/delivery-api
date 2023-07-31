<?php

namespace App\Helpers;

class PayloadHelper
{
    public static function preparePayload(array $attributes): array
    {
        return [
            "data" => [
                "attributes" => $attributes
            ]
        ];
    }
}
