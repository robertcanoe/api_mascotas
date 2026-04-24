<?php

declare(strict_types=1);

namespace App\Forms;

class MascotaForm
{
    private MascotaSanitizer $sanitizer;
    private MascotaValidator $validator;

    public function __construct()
    {
        $this->sanitizer = new MascotaSanitizer();
        $this->validator = new MascotaValidator();
    }

    public function validate(array $input): array
    {
        $sanitized = $this->sanitizer->sanitize($input);
        $errors = $this->validator->validate($sanitized);

        return [
            'is_valid' => $errors === [],
            'errors' => $errors,
            'data' => $sanitized,
            'form' => $input,
        ];
    }
}
