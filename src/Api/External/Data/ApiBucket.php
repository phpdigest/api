<?php

declare(strict_types=1);

namespace App\Api\External\Data;

use roxblnfk\SmartStream\Data\DataBucket;

class ApiBucket extends DataBucket
{
    public function getData(): array
    {
        return [
            'success' => true,
            'status' => $this->getStatusCode() ?? 200,
            'data' => $this->data,
            'code' => null,
            'message' => null,
        ];
    }
}
