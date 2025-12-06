<?php

declare(strict_types=1);

namespace frontend\models\Apple\Domain;

use common\models\Domain\Percentage;

final class Apple
{
    private bool $toDeleteCrutch = false;

    private function __construct(
        private ?AppleId $id = null,
        private AppleState $state,
        private AppleSize $size,
        private readonly AppleColor $color,
        private AppleFallenTimestamp $createdAt,
        private ?AppleFallenTimestamp $fellAt = null
    ) {

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

    public function createdAt(): ?AppleFallenTimestamp
    {
        return $this->fellAt;
    }

    public function fellAt(): ?AppleFallenTimestamp
    {
        return $this->fellAt;
    }

    public function eat(Percentage $percentage): self
    {
        if (!$this->state->isEatable()) {
            // TODO: throw more specific exception
            throw new \DomainException('Apple cannot be eaten.');
        }

        if ($percentage->greaterThan($left = $this->size()->percentage())) {
            // TODO: throw more specific exception
            throw new \DomainException(
                sprintf('Cannot eat more than %d%% of apple.', $left)
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
