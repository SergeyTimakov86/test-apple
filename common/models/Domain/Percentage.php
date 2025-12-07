<?php

declare(strict_types=1);

namespace common\models\Domain;

final readonly class Percentage extends BaseIntegerValue
{
    protected function __construct(private int $value)
    {
        parent::__construct($value);

        if ($value < 0 || $value > 100) {
            // TODO: throw specific InvalidPercentage
            throw new \DomainException('Wrong percentage value: ' . $value);
        }
    }
}
