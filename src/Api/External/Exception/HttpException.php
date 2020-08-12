<?php

declare(strict_types=1);

namespace App\Api\External\Exception;

use Exception;
use Throwable;

class HttpException extends Exception
{
    private int $status;
    public function __construct(int $status = 500, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status = $status;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
}
