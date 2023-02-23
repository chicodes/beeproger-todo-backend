<?php

    namespace App\Utils;

    class Utilities
    {
        public static function getResponse(string $message, bool $status, $code,$data = "", array $errors = []): array {
            return [
                "status"  => $status,
                "code"    => $code,
                "message" => $message,
                "data" => $data
                //"errors"  => $errors,
            ];
        }
    }
