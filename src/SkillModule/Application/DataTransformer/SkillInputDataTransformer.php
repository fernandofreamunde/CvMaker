<?php

declare(strict_types=1);

namespace App\SkillModule\Application\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\Skill;
use App\SkillModule\Domain\Dto\SkillInputDto;
use App\SkillModule\Domain\Enum\SkillTypeEnum;
use App\SkillModule\Domain\Exception\InvalidSkillType;
use Exception;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class SkillInputDataTransformer implements DataTransformerInterface
{

    public function transform($object, string $to, array $context = []): object
    {
        if (isset($context[AbstractNormalizer::OBJECT_TO_POPULATE])) {
            $skill = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
        } else {
            $skill = new Skill();
        }

        $skill->setContent($object->content);
        try {
            $skill->setType(new SkillTypeEnum($object->type));
        } catch (Exception $exception) {
            throw new InvalidSkillType();
        }

        return $skill;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Skill) {
            // already transformed
            return false;
        }

        return $to === Skill::class && ($context['input']['class'] ?? null) === SkillInputDto::class;
    }
}
