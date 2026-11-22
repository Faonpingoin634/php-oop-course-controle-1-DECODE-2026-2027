<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repository\ContactRepository;

class DeleteContactController extends AbstractController {
    public function process(Request $request): Response
    {
        $filename = $request->getParam('filename');

        $repository = new ContactRepository();

        if ($repository->delete($filename) === false) {
            return $this->errorResponse(404, 'Contact not found');
        }

        $response = new Response();
        $response->statusCode = 204;

        return $response;
    }
}