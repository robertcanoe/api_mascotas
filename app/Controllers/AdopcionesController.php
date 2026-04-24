<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AdopcionService;

class AdopcionesController extends ApiController
{
    private AdopcionService $service;

    public function __construct()
    {
        $this->service = new AdopcionService();
    }

    public function storeAction(array $request = []): void
    {
        $this->ensurePostRequest();

        $result = $this->service->createAdopcion($this->getRequestData());
        $status = (int) ($result['status'] ?? 500);

        if (($result['success'] ?? false) === true) {
            $this->respond([
                'message' => $result['message'] ?? 'Solicitud procesada.',
                'data' => $result['data'] ?? null,
            ], $status);
            return;
        }

        $this->respondError(
            (string) ($result['message'] ?? 'No fue posible procesar la solicitud.'),
            $status,
            $result['errors'] ?? []
        );
    }
}
