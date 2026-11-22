<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repository\ContactRepository;

class GetContactController extends AbstractController {
    public function process(Request $request): Response
    {
        $filename = $request->getParam('filename');

        $repository = new ContactRepository();
        $contact = $repository->find($filename);

        if ($contact === null) {
            return $this->errorResponse(404, 'Contact not found');
        }

        return $this->jsonResponse(200, $contact);
    }
}