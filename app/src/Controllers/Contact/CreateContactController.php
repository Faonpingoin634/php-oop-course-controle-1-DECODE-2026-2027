<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repository\ContactRepository;
use App\Validator\ContactValidator;

class CreateContactController extends AbstractController {

    public function process(Request $request): Response
    {
        if ($this->isJsonRequest($request) === false) {
            return $this->errorResponse(400, 'Content-Type must be application/json');
        }

        $data = json_decode($request->getBody(), true);
        if (is_array($data) === false || array_is_list($data)) {
            return $this->errorResponse(400, 'Body must be a valid JSON object');
        }

        $validator = new ContactValidator();
        $error = $validator->validate($data);
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
}