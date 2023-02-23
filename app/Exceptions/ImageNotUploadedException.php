<?php

namespace App\Exceptions;

use Exception;

class ImageNotUploadedException extends Exception
{
    public function render($request)
    {
        return response()->json(["errors" => true, "message" => $this->getMessage(), "code" => $this->getCode()]);
    }
}


