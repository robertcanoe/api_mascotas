<?php

declare(strict_types=1);

namespace App\Core;

use Exception;

class HttpException extends Exception
{
    public function __construct(int $statusCode, string $message)
    {
        parent::__construct($message, $statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->getCode() > 0 ? $this->getCode() : 500;
    }
}
