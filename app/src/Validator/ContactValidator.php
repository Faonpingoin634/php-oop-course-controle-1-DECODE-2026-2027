<?php

namespace App\Validator;

class ContactValidator {
    private const array ALLOWED_FIELDS = ['email', 'subject', 'message'];

    public function validate(array $data, bool $partial = false): ?string
    {
        $extraFields = array_diff(array_keys($data), self::ALLOWED_FIELDS);
        if (count($extraFields) > 0) {
            return 'Unknown field(s): ' . implode(', ', $extraFields);
        }

        if ($partial && count($data) === 0) {
            return 'At least one field is required: ' . implode(', ', self::ALLOWED_FIELDS);
        }

        foreach (self::ALLOWED_FIELDS as $field) {
            if (array_key_exists($field, $data) === false) {
                if ($partial) {
                    continue;
                }
                return 'Missing field: ' . $field;
            }

            if (is_string($data[$field]) === false || trim($data[$field]) === '') {
                return 'Field ' . $field . ' must be a non-empty string';
            }
        }

        if (array_key_exists('email', $data) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            return 'Field email must be a valid email';
        }

        return null;
    }
}