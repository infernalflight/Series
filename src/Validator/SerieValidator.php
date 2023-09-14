<?php

namespace App\Validator;

use App\Entity\Serie;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SerieValidator
{
    public static function validate(Serie $serie, ExecutionContextInterface $context): void
    {
        if (\in_array($serie->getStatus(), ['Canceled', 'ended']) && !$serie->getLastAirDate()) {
            $context->buildViolation('Si la série est canceled alors il faut renseigner une date de fin')
                ->atPath('lastAirDate')
                ->addViolation();
        }

        if (!\in_array($serie->getStatus(), ['Canceled', 'ended']) && $serie->getLastAirDate()) {
            $context->buildViolation('Si la série continue alors pourquoi une date de fin ?')
                ->atPath('lastAirDate')
                ->addViolation();
        }
    }
}