<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET', 'PUT', 'DELETE']
)]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $schoolName;

    #[ORM\Column(type: 'string', length: 255)]
    private $degreeName;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private $graduationYear;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolName(): ?string
    {
        return $this->schoolName;
    }

    public function setSchoolName(string $schoolName): self
    {
        $this->schoolName = $schoolName;

        return $this;
    }

    public function getDegreeName(): ?string
    {
        return $this->degreeName;
    }

    public function setDegreeName(string $degreeName): self
    {
        $this->degreeName = $degreeName;

        return $this;
    }

    public function getGraduationYear(): ?string
    {
        return $this->graduationYear;
    }

    public function setGraduationYear(?string $graduationYear): self
    {
        $this->graduationYear = $graduationYear;

        return $this;
    }
}
