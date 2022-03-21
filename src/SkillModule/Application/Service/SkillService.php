<?php

declare(strict_types=1);

namespace App\SkillModule\Application\Service;

use App\Common\DataPersister\ResourceServiceInterface;
use App\Entity\Skill;
use App\Repository\SkillRepository;

class SkillService implements ResourceServiceInterface
{
    private SkillRepository $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    public function create($entity)
    {
        $this->skillRepository->commit($entity);
        $this->skillRepository->save();
    }

    public function update($entity)
    {
        $this->skillRepository->commit($entity);
        $this->skillRepository->save();
    }

    public function delete($entity)
    {
        $this->skillRepository->delete($entity);
        $this->skillRepository->save();
    }
}
