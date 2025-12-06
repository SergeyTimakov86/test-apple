<?php

declare(strict_types=1);

namespace common\models\Domain;

abstract readonly class BaseIntegerValue implements IntegerValue
{
    protected function __construct(private int $value)
    {
    }

    final public function greaterThan(self $other): bool
    {
        // TODO: late static binding type equality check
        return $this->value() > $other->value();
    }

    final public static function of(int $value): static
    {
        return new static($value);
    }

    final public function value(): int
    {
        return $this->value;
    }
}
