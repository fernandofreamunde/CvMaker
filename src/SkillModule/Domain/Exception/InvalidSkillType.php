<?php

declare(strict_types=1);

namespace App\SkillModule\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


class InvalidSkillType extends HttpException
{
    public function __construct()
    {
        $message = 'Invalid Skill Type.';

        parent::__construct(Response::HTTP_BAD_REQUEST, $message, null, [], 0);
    }
}
