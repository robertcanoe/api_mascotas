<?php

declare(strict_types=1);

namespace App\Services;

use App\Forms\MascotaForm;
use App\Models\DatabaseException;
use App\Models\MascotaModel;

class MascotaService
{
    private MascotaModel $model;
    private MascotaForm $form;

    public function __construct()
    {
        $this->model = new MascotaModel();
        $this->form = new MascotaForm();
    }

    public function listMascotas(): array
    {
        try {
            return $this->model->getAll();
        } catch (DatabaseException $exception) {
            $exception->logError();
            return $this->seedData();
        }
    }

    public function getMascota(int $id): ?array
    {
        try {
            return $this->model->findById($id);
        } catch (DatabaseException $exception) {
            $exception->logError();

            foreach ($this->seedData() as $item) {
                if ((int) $item['id'] === $id) {
                    return $item;
                }
            }

            return null;
        }
    }

    public function createMascota(array $input): array
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

        try {
            $id = $this->model->create($validation['data']);
            return [
                'success' => true,
                'status' => 201,
                'data' => ['id' => $id] + $validation['data'],
            ];
        } catch (DatabaseException $exception) {
            $exception->logError();
            return [
                'success' => false,
                'status' => 500,
                'message' => 'No fue posible crear la mascota.',
            ];
        }
    }

    public function updateMascota(int $id, array $input): array
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

        try {
            $this->model->update($id, $validation['data']);
            return [
                'success' => true,
                'status' => 200,
                'data' => ['id' => $id] + $validation['data'],
            ];
        } catch (DatabaseException $exception) {
            $exception->logError();
            return [
                'success' => false,
                'status' => 500,
                'message' => 'No fue posible actualizar la mascota.',
            ];
        }
    }

    public function deleteMascota(int $id): array
    {
        try {
            $deleted = $this->model->delete($id);
            if (!$deleted) {
                return [
                    'success' => false,
                    'status' => 404,
                    'message' => 'Mascota no encontrada.',
                ];
            }

            return [
                'success' => true,
                'status' => 200,
                'message' => 'Mascota eliminada correctamente.',
            ];
        } catch (DatabaseException $exception) {
            $exception->logError();
            return [
                'success' => false,
                'status' => 500,
                'message' => 'No fue posible eliminar la mascota.',
            ];
        }
    }

    private function seedData(): array
    {
        return require CONFIG_PATH . '/seed_mascotas.php';
    }
}
