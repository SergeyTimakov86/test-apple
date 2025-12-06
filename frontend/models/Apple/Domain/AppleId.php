<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\BaseIntegerValue;

final readonly class AppleId extends BaseIntegerValue
{
    protected function __construct(private int $value)
    {
        parent::__construct($value);

        if ($value < 1) {
            // TODO: throw specific InvalidAppleId
            throw new \DomainException('Apple identifier is expected to be unsigned integer, given: ' . $value);
        }
    }
}
