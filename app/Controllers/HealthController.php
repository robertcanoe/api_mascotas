<?php

declare(strict_types=1);

namespace App\Controllers;

class HealthController extends ApiController
{
    public function statusAction(array $request = []): void
    {
        $this->respond([
            'data' => [
                'status' => 'ok',
                'timestamp' => date('c'),
                'env' => APP_ENV,
            ],
        ]);
    }
}
