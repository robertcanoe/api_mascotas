<?php

declare(strict_types=1);

namespace App\Forms;

class AdopcionForm
{
    private AdopcionSanitizer $sanitizer;
    private AdopcionValidator $validator;

    public function __construct()
    {
        $this->sanitizer = new AdopcionSanitizer();
        $this->validator = new AdopcionValidator();
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
