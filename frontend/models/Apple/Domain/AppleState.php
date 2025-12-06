<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\IntegerValue;

enum AppleState: int implements IntegerValue
{
    case ON_TREE = 0;
    case FALLEN = 1;
    case ROTTEN = 2;

    public static function of(int $value): self
    {
        try {
            return self::from($value);
        } catch (\Throwable $thr) {
            // TODO: throw specific InvalidAppleState
            throw new \DomainException($thr->getMessage());
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function isEatable(): bool
    {
        return in_array($this, [self::FALLEN,]);
    }
}
