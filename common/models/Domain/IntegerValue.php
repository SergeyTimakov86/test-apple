<?php

declare(strict_types=1);

namespace common\models\Domain;

interface IntegerValue
{
    /**
     * @throws \DomainException
     */
    public static function of(int $value): self;
    public function value(): int;
}
