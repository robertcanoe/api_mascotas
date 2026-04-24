<?php

declare(strict_types=1);

namespace App\Models;

use Exception;

class DatabaseException extends Exception
{
    public function logError(): void
    {
        app_log('DatabaseException: ' . $this->getMessage(), 'ERROR');
    }
}
