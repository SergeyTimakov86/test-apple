<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\IntegerValue;

enum AppleColor: int implements IntegerValue
{
    case RED = 0;
    case GREEN = 1;
    case YELLOW = 2;
    case PINK = 3;

    public static function of(int $value): self
    {
        try {
            return self::from($value);
        } catch (\Throwable $thr) {
            // TODO: throw specific InvalidAppleColor
            throw new \DomainException($thr->getMessage());
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
