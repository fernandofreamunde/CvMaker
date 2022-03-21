<?php

declare(strict_types=1);

namespace App\SkillModule\Domain\Dto;

use App\SkillModule\Domain\Enum\SkillTypeEnum;

class SkillInputDto
{
    public string $type;
    public string $content;
}
