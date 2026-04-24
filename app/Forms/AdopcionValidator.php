<?php

declare(strict_types=1);

namespace App\Forms;

class AdopcionValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if ($data['mascota_id'] <= 0) {
            $errors['mascota_id'] = 'Debe seleccionar una mascota valida.';
        }

        if ($data['solicitante'] === '' || mb_strlen($data['solicitante']) < 2) {
            $errors['solicitante'] = 'El nombre del solicitante es obligatorio.';
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'El correo electronico no es valido.';
        }

        if (mb_strlen($data['mensaje']) > 500) {
            $errors['mensaje'] = 'El mensaje no puede superar 500 caracteres.';
        }

        return $errors;
    }
}
