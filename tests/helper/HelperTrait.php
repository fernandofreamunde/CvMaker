<?php

declare(strict_types=1);

namespace App\Tests\helper;

use Exception;

trait HelperTrait
{
    /**
     * @throws Exception
     */
    private function getRandomItem(array $array)
    {
        return $array[random_int(0, count($array) - 1)];
    }
}