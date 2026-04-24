<?php

declare(strict_types=1);

namespace App\Forms;

class AuthLoginForm
{
    private AuthLoginSanitizer $sanitizer;
    private AuthLoginValidator $validator;

    public function __construct()
    {
        $this->sanitizer = new AuthLoginSanitizer();
        $this->validator = new AuthLoginValidator();
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
