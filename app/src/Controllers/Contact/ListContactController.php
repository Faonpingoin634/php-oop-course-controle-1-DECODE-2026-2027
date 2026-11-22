<?php

namespace App\Controllers\Contact;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Repository\ContactRepository;

class ListContactController extends AbstractController {
    public function process(Request $request): Response
    {
        $repository = new ContactRepository();

        return $this->jsonResponse(200, $repository->findAll());
    }
}