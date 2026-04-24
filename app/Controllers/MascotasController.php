<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\HttpException;
use App\Services\MascotaService;

class MascotasController extends ApiController
{
    private MascotaService $service;

    public function __construct()
    {
        $this->service = new MascotaService();
    }

    public function indexAction(array $request = []): void
    {
        $this->respond([
            'data' => $this->service->listMascotas(),
        ]);
    }

    public function showAction(string $id, array $request = []): void
    {
        $mascotaId = (int) $id;
        if ($mascotaId <= 0) {
            throw new HttpException(400, 'Invalid mascota id.');
        }

        $mascota = $this->service->getMascota($mascotaId);
        if ($mascota === null) {
            throw new HttpException(404, 'Mascota not found.');
        }

        $this->respond(['data' => $mascota]);
    }

    public function storeAction(array $request = []): void
    {
        $this->ensurePostRequest();

        $result = $this->service->createMascota($this->getRequestData());
        $this->handleServiceResult($result);
    }

    public function updateAction(string $id, array $request = []): void
    {
        $this->ensurePostRequest();

        $mascotaId = (int) $id;
        if ($mascotaId <= 0) {
            throw new HttpException(400, 'Invalid mascota id.');
        }

        $result = $this->service->updateMascota($mascotaId, $this->getRequestData());
        $this->handleServiceResult($result);
    }

    public function deleteAction(string $id, array $request = []): void
    {
        $this->ensurePostRequest();

        $mascotaId = (int) $id;
        if ($mascotaId <= 0) {
            throw new HttpException(400, 'Invalid mascota id.');
        }

        $result = $this->service->deleteMascota($mascotaId);
        $this->handleServiceResult($result);
    }

    private function handleServiceResult(array $result): void
    {
        $status = (int) ($result['status'] ?? 500);

        if (($result['success'] ?? false) === true) {
            $this->respond([
                'message' => $result['message'] ?? 'OK',
                'data' => $result['data'] ?? null,
            ], $status);
            return;
        }

        $this->respondError(
            (string) ($result['message'] ?? 'Validation error'),
            $status,
            $result['errors'] ?? []
        );
    }
}
