<?php

namespace App\Libraries;

use CodeIgniter\API\ResponseTrait;

trait SendResponseTrait
{
    use ResponseTrait;

    public function getInstance()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function sendRespond(array $data = null, int $status = null, string $message = "")
    {
        $this->respond($data, $status, $message)->send();
        exit(EXIT_SUCCESS);
    }

    public function success($data = null, int $status = 200, string $message = 'success.')
    {
        if (!is_array($data)) {
            $data = [
                'status' => $status,
                'data' => $message
            ];
        }

        $this->sendRespond($data, $status, $message);
    }

    public function error($data, $status = 400, string $message = 'error.')
    {
        if (!is_array($data)) {
            $data = [
                'status' => $status,
                'data' => $data
            ];
        }
        $this->sendRespond($data, $status, $message);
    }

    public function generateErrorMessage(array $invalidParameters = null, string $errorType = null, string $tittle = null, string $instance = null): array
    {
        $errorMessage = [
            'status' => 'error',
            'error_type' => $errorType ?? 'validation',
            'title' => $title ?? 'Your request parameters did not pass our validation.',
            'instance' => $instance ?? $this->getInstance(),
            'invalid_parameters' => $invalidParameters,
        ];
        if ($invalidParameters == null) unset($errorMessage['invalid_parameters']);
        return $errorMessage;
    }

    public function sendValidateionFaild($data, int $status = 400, string $customMessage = '')
    {
        $this->respond($data, $status, $customMessage)->setHeader("Contect-Type", "application/problem+json")->send();
        exit(EXIT_ERROR);
    }

}