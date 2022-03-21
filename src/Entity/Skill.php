<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Common\Entity;
use App\Repository\SkillRepository;
use App\SkillModule\Domain\Dto\SkillInputDto;
use App\SkillModule\Domain\Enum\SkillTypeEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET', 'PUT', 'DELETE'],
    input: SkillInputDto::class,
)]
class Skill extends Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     *  Must be one of the following: ['language', 'programming language', 'technologies', 'tools']
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?SkillTypeEnum
    {
        return SkillTypeEnum::from($this->type);
    }

    public function setType(SkillTypeEnum $type): self
    {
        $this->type = $type->getValue();

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
