<?php

namespace App\Exceptions;

use Exception;

class TodoPhotoException extends Exception
{
    public function render($request)
    {
        return response()->json(["errors" => true, "message" => $this->getMessage(), "code" => $this->getCode()]);
    }
}


