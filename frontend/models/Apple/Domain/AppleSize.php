<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\BaseIntegerValue;
use common\models\Domain\FloatValue;
use common\models\Domain\IntegerValue;
use common\models\Domain\Percentage;

final readonly class AppleSize implements FloatValue
{
    public function __construct(private float $value)
    {
        if ($value < 0 || $value > 1) {
            // TODO: throw specific InvalidAppleSize
            throw new \DomainException('Wrong apple size: ' . $value);
        }
    }

    public static function of(float $value): self
    {
        return new self($value);
    }

    public function value(): float
    {
        return $this->value;
    }

    public function percentage(): Percentage
    {
        return Percentage::of((int) ($this->value * 100));
    }
}
