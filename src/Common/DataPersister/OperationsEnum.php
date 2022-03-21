<?php

declare(strict_types=1);

namespace App\Common\DataPersister;

use MyCLabs\Enum\Enum;

/**
 * Class Operations
 *
 * @method static OperationsEnum CREATE()
 * @method static OperationsEnum READ()
 * @method static OperationsEnum UPDATE()
 * @method static OperationsEnum PATCH()
 * @method static OperationsEnum DELETE()
 * @method static OperationsEnum LIST()
 */
class OperationsEnum extends Enum
{
    private const CREATE = 'create';
    private const READ   = 'read';
    private const UPDATE = 'update';
    private const PATCH  = 'patch';
    private const DELETE = 'delete';
    private const LIST   = 'list';
}
