<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repository\ContactRepository;

class CreateContactController extends AbstractController {
    private const array ALLOWED_FIELDS = ['email', 'subject', 'message'];

    public function process(Request $request): Response
    {
        if ($this->isJsonRequest($request) === false) {
            return $this->errorResponse(400, 'Content-Type must be application/json');
        }

        $data = json_decode($request->getBody(), true);
        if (is_array($data) === false || array_is_list($data)) {
            return $this->errorResponse(400, 'Body must be a valid JSON object');
        }

        $error = $this->validate($data);
        if ($error !== null) {
            return $this->errorResponse(400, $error);
        }

        $now = time();
        $contact = [
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'dateOfCreation' => $now,
            'dateOfLastUpdate' => $now,
        ];

        $filename = $now . '_' . $data['email'] . '.json';
        $repository = new ContactRepository();
        $repository->save($filename, $contact);

        return $this->jsonResponse(201, ['file' => $filename]);
    }

    private function validate(array $data): ?string
    {
        $extraFields = array_diff(array_keys($data), self::ALLOWED_FIELDS);
        if (count($extraFields) > 0) {
            return 'Unknown field(s): ' . implode(', ', $extraFields);
        }

        foreach (self::ALLOWED_FIELDS as $field) {
            if (array_key_exists($field, $data) === false) {
                return 'Missing field: ' . $field;
            }
            if (is_string($data[$field]) === false || trim($data[$field]) === '') {
                return 'Field ' . $field . ' must be a non-empty string';
            }
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            return 'Field email must be a valid email';
        }

        return null;
    }
}