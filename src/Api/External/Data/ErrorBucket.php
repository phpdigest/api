<?php

declare(strict_types=1);

namespace App\Api\External\Data;

use App\Api\External\Exception\HttpException;

class ErrorBucket extends ApiBucket
{
    protected \Throwable $error;
    private bool $modeDev;

    public function __construct(\Throwable $error, bool $modeDev = false)
    {
        $this->error = $error;
        $this->modeDev = $modeDev;
        if ($error instanceof HttpException) {
            $this->setStatusCode($error->getStatus());
        }
        parent::__construct('');
    }

    public function getData(): array
    {
        $data = parent::getData();
        $data['success'] = false;
        $data['data'] = null;
        $data['code'] = $this->error->getCode();
        $data['message'] = $this->error->getMessage();
        if ($this->modeDev) {
            $data['file'] = $this->error->getFile();
            $data['line'] = $this->error->getLine();
            $data['stack'] = $this->error->getTrace();
        }
        return $data;
    }
}
