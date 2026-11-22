<?php

namespace App\Lib\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;

abstract class AbstractController {
    public abstract function process(Request $request): Response;

    protected function jsonResponse(int $statusCode, mixed $data = null): Response
    {
        $response = new Response();
        $response->statusCode = $statusCode;
        $response->headers['Content-Type'] = 'application/json';

        if ($data !== null) {
            $response->content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return $response;
    }

    protected function errorResponse(int $statusCode, string $message): Response
    {
        return $this->jsonResponse($statusCode, ['error' => $message]);
    }

    protected function isJsonRequest(Request $request): bool
    {
        $contentType = $request->getHeader('Content-Type') ?? '';

        return str_starts_with(strtolower(trim($contentType)), 'application/json');
    }
}