<?php

declare(strict_types=1);

namespace App\SkillModule\Application\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Common\DataPersister\CommonDataPersister;
use App\Entity\Skill;
use App\SkillModule\Application\Service\SkillService;

class SkillDataPersister extends CommonDataPersister implements ContextAwareDataPersisterInterface
{

    public function __construct(SkillService $skillService)
    {
        $this->resourceService = $skillService;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Skill;
    }
}
