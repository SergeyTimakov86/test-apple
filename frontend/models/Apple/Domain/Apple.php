<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\Percentage;

final class Apple
{
    private bool $toDeleteCrutch = false;
    private bool $rottedCrutch = false;

    private function __construct(
        private AppleId|null $id,
        private AppleState $state,
        private AppleSize $size,
        private readonly AppleColor $color,
        private readonly AppleCreatedTimestamp $createdAt,
        private AppleFallenTimestamp|null $fellAt = null,
        int $timeToRot
    ) {
        // autotransitions

        if ($this->fellAt() && $this->state() !== AppleState::ROTTEN) {
            // TODO: use some ClockInterface as method argument, time(), randomeizers, etc.. implementations are all about Infrastructure Layer
            $timeSinceFell = time() - $this->fellAt()->value();

            if ($timeSinceFell >= $timeToRot) {
                $this->transitState(AppleState::ROTTEN);
                // Crutch. TODO: record events and react on them from the outside
                $this->rottedCrutch = true;
            }
        }
    }

    public static function create(
        int|null $id,
        int $state,
        float $size,
        int $color,
        int $createdAt,
        int|null $fellAt,
        int $timeToRot
    ): self {
        return new self(
            id: $id ? AppleId::of($id) : null,
            state: AppleState::of($state),
            size: AppleSize::of($size),
            color: AppleColor::of($color),
            createdAt: AppleCreatedTimestamp::of($createdAt),
            fellAt: $fellAt ? AppleFallenTimestamp::of($fellAt) : null,
            timeToRot: $timeToRot,
        );
    }

    public function id(): ?AppleId
    {
        return $this->id;
    }

    public function state(): AppleState
    {
        return $this->state;
    }

    public function size(): AppleSize
    {
        return $this->size;
    }

    public function color(): AppleColor
    {
        return $this->color;
    }

    public function createdAt(): AppleCreatedTimestamp
    {
        return $this->createdAt;
    }

    public function fellAt(): ?AppleFallenTimestamp
    {
        return $this->fellAt;
    }

    public function eat(Percentage $percentage): self
    {
        $idx = $this->id() ? '#' . $this->id()->value() : '';

        if (!$this->state->isEatable()) {
            // TODO: throw more specific exception
            throw new \DomainException(
                sprintf('Apple%s cannot be eaten.', $idx)
            );
        }

        if ($percentage->greaterThan($left = $this->size()->percentage())) {
            // TODO: throw more specific exception
            throw new \DomainException(
                sprintf('Cannot eat more than %d%% of apple%s.', $left->value(), $idx)
            );
        }

        // TODO: basic math-methods in Basic<Type>Value classes
        $this->size = AppleSize::of(
            $this->size()->value() - $percentage->value() / 100
        );

        if ($this->size->value() === .0) {
            // Crutch. TODO: record events and react on them from the outside
            $this->toDeleteCrutch = true;
        }

        return $this;
    }

    public function toDeleteCrutch(): bool
    {
        return $this->toDeleteCrutch;
    }

    public function rottedCrutch(): bool
    {
        return $this->rottedCrutch;
    }

    private function transitState(AppleState $to): self
    {
        $this->state = AppleStateTransition::between($this->state, $to)->to();
        return $this;
    }

    public function fallToGround(): self
    {
        // TODO: use some ClockInterface as method argument, time(), randomeizers, etc.. implementations are all about Infrastructure Layer
        $this->fellAt = AppleFallenTimestamp::of(time());
        return $this->transitState(AppleState::FALLEN);
    }
}
