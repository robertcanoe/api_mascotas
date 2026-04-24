<?php

declare(strict_types=1);

namespace App\Forms;

class AdopcionSanitizer
{
    public function sanitize(array $input): array
    {
        return [
            'mascota_id' => (int) ($input['mascota_id'] ?? 0),
            'solicitante' => trim((string) ($input['solicitante'] ?? '')),
            'email' => trim((string) ($input['email'] ?? '')),
            'mensaje' => trim((string) ($input['mensaje'] ?? '')),
        ];
    }
}
