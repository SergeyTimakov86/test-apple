<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\BaseIntegerValue;

final readonly class AppleCreatedTimestamp extends BaseIntegerValue
{
    protected function __construct(private int $value)
    {
        parent::__construct($value);

        if ($value < 1) {
            // TODO: throw specific InvalidAppleCreatedTimestamp
            throw new \DomainException('Apple creation time is expected to be unix-timestamp, given: ' . $value);
        }
    }
}
