<?php

declare(strict_types=1);

namespace App\SkillModule\Domain\Enum;

use MyCLabs\Enum\Enum;
/**
 * @method static SkillTypeEnum LANGUAGE()
 * @method static SkillTypeEnum PROGRAMMING_LANGUAGE()
 * @method static SkillTypeEnum TECHNOLOGY()
 * @method static SkillTypeEnum TOOL()
 */
class SkillTypeEnum extends Enum
{
    private const LANGUAGE             = 'language';
    private const PROGRAMMING_LANGUAGE = 'programming language';
    private const TECHNOLOGY           = 'technology';
    private const TOOL                 = 'tool';
}
