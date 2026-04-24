<?php

declare(strict_types=1);

namespace App\Forms;

class MascotaSanitizer
{
    public function sanitize(array $input): array
    {
        return [
            'nombre' => trim((string) ($input['nombre'] ?? '')),
            'especie' => trim((string) ($input['especie'] ?? '')),
            'edad' => (int) ($input['edad'] ?? 0),
            'foto_url' => trim((string) ($input['foto_url'] ?? '')),
            'descripcion' => trim((string) ($input['descripcion'] ?? '')),
        ];
    }
}
