<?php

declare(strict_types=1);

namespace App\Forms;

class MascotaValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if ($data['nombre'] === '' || mb_strlen($data['nombre']) < 2) {
            $errors['nombre'] = 'El nombre es obligatorio y debe tener al menos 2 caracteres.';
        }

        if ($data['especie'] === '' || mb_strlen($data['especie']) < 2) {
            $errors['especie'] = 'La especie es obligatoria.';
        }

        if ($data['edad'] < 0 || $data['edad'] > 40) {
            $errors['edad'] = 'La edad debe estar entre 0 y 40.';
        }

        if ($data['foto_url'] !== '' && filter_var($data['foto_url'], FILTER_VALIDATE_URL) === false) {
            $errors['foto_url'] = 'La foto debe ser una URL valida.';
        }

        if (mb_strlen($data['descripcion']) > 500) {
            $errors['descripcion'] = 'La descripcion no puede superar 500 caracteres.';
        }

        return $errors;
    }
}
