<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

final readonly class AppleStateTransition
{
    private const array VALID_TRANSITIONS = [
        AppleState::ON_TREE->name => [AppleState::FALLEN],
        AppleState::FALLEN->name => [AppleState::ROTTEN],
        AppleState::ROTTEN->name => [],
    ];

    public static function between(AppleState $from, AppleState $to): self
    {
        return new self($from, $to);
    }

    private function __construct(
        private AppleState $from,
        private AppleState $to
    ) {
        if (!isset(self::VALID_TRANSITIONS[$from->name])) {
            // TODO: throw specific exception
            throw new \DomainException('State transitions not declared for "' . $from->name . '" state');
        }

        if (!in_array($to, self::VALID_TRANSITIONS[$from->name])) {
            // TODO: throw more ubiquitous specific exceptionS
            throw new \DomainException('State transition from "' . $from->name . '" to "' . $to->name . '" is not allowed');
        }
    }

    public function from(): AppleState
    {
        return $this->from;
    }

    public function to(): AppleState
    {
        return $this->to;
    }
}
