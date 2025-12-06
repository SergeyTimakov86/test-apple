<?php

declare(strict_types=1);

namespace common\models\Domain;

interface FloatValue
{
    /**
     * @throws \DomainException
     */
    public static function of(float $value): self;
    public function value(): float;
}
