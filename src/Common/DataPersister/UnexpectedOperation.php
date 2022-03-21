<?php

declare(strict_types=1);

namespace App\Common\DataPersister;

use Exception;

class UnexpectedOperation extends Exception
{
    public function __construct(string $operation = "")
    {
        parent::__construct(sprintf('Unexpected operation: %s.', $operation) , 0, null);
    }
}
