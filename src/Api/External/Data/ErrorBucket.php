<?php

declare(strict_types=1);

namespace App\Api\External\Data;

class ErrorBucket extends ApiBucket
{
    protected \Throwable $error;
    private bool $modeDev;

    public function __construct(\Throwable $error, bool $modeDev = false)
    {
        $this->error = $error;
        $this->modeDev = $modeDev;
        parent::__construct('');
    }

    public function getData(): array
    {
        $data = [
            'code' => $this->error->getCode(),
            'message' => $this->error->getMessage(),
        ];
        if ($this->modeDev) {
            $data['file'] = $this->error->getFile();
            $data['line'] = $this->error->getLine();
            $data['stack'] = $this->error->getTrace();
        }
        return $data;
    }
}
