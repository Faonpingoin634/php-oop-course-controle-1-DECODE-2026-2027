<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repository\ContactRepository;
use App\Validator\ContactValidator;

class UpdateContactController extends AbstractController {
    public function process(Request $request): Response
    {
        $filename = $request->getParam('filename');

        $repository = new ContactRepository();
        $contact = $repository->find($filename);

        if ($contact === null) {
            return $this->errorResponse(404, 'Contact not found');
        }

        if ($this->isJsonRequest($request) === false) {
            return $this->errorResponse(400, 'Content-Type must be application/json');
        }

        $data = json_decode($request->getBody(), true);

        if (is_array($data) === false || array_is_list($data)) {
            return $this->errorResponse(400, 'Body must be a valid JSON object');
        }

        $validator = new ContactValidator();
        $error = $validator->validate($data, true);
        if ($error !== null) {
            return $this->errorResponse(400, $error);
        }

        foreach ($data as $field => $value) {
            $contact[$field] = $value;
        }
        $contact['dateOfLastUpdate'] = time();

        $repository->save($filename, $contact);

        return $this->jsonResponse(200, $contact);
    }
}