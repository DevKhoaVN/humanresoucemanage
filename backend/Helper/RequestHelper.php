<?php

namespace Helper;

class RequestHelper
{

    public static function getJsonBody(): array
    {
        $raw = file_get_contents("php://input");

        if (!$raw) {
            throw new InvalidArgumentException("Request body is empty!");
        }

        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Invalid JSON format: " . json_last_error_msg());
        }

        return $data;
    }


}