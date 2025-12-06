<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\BaseIntegerValue;

final readonly class AppleFallenTimestamp extends BaseIntegerValue
{
    protected function __construct(private int $value)
    {
        parent::__construct($value);

        if ($value < 1) {
            // TODO: throw specific InvalidAppleFallenTimestamp
            throw new \DomainException('Apple fallen time is expected to be unix-timestamp, given: ' . $value);
        }
    }
}
