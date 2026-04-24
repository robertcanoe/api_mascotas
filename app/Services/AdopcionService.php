<?php

declare(strict_types=1);

namespace App\Services;

use App\Forms\AdopcionForm;
use App\Models\AdopcionModel;
use App\Models\DatabaseException;
use App\Models\MascotaModel;

class AdopcionService
{
    private AdopcionForm $form;
    private AdopcionModel $adopcionModel;
    private MascotaModel $mascotaModel;

    public function __construct()
    {
        $this->form = new AdopcionForm();
        $this->adopcionModel = new AdopcionModel();
        $this->mascotaModel = new MascotaModel();
    }

    public function createAdopcion(array $input): array
    {
        $validation = $this->form->validate($input);
        if (!$validation['is_valid']) {
            return [
                'success' => false,
                'status' => 400,
                'errors' => $validation['errors'],
                'form' => $validation['form'],
            ];
        }

        $data = $validation['data'];

        try {
            $mascota = $this->mascotaModel->findById((int) $data['mascota_id']);
            if ($mascota === null) {
                return [
                    'success' => false,
                    'status' => 404,
                    'message' => 'La mascota seleccionada no existe.',
                ];
            }

            $id = $this->adopcionModel->create($data);
            return [
                'success' => true,
                'status' => 201,
                'data' => [
                    'id' => $id,
                    'mascota_id' => $data['mascota_id'],
                    'solicitante' => $data['solicitante'],
                    'email' => $data['email'],
                ],
            ];
        } catch (DatabaseException $exception) {
            $exception->logError();
            $this->storeFallback($data);

            return [
                'success' => true,
                'status' => 202,
                'message' => 'Solicitud recibida. Se almacenara cuando la base de datos este disponible.',
            ];
        }
    }

    private function storeFallback(array $data): void
    {
        $path = CACHE_PATH . '/adopciones_fallback.json';
        $current = [];

        if (is_file($path)) {
            $decoded = json_decode((string) file_get_contents($path), true);
            if (is_array($decoded)) {
                $current = $decoded;
            }
        }

        $current[] = [
            'id' => uniqid('offline_', true),
            'mascota_id' => $data['mascota_id'],
            'solicitante' => $data['solicitante'],
            'email' => $data['email'],
            'mensaje' => $data['mensaje'],
            'created_at' => date('c'),
        ];

        file_put_contents($path, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
