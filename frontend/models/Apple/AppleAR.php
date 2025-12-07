<?php

namespace frontend\models\Apple;

use common\models\Domain\Percentage;
use frontend\models\Apple\Domain\Apple;
use frontend\models\Apple\Domain\AppleColor;
use frontend\models\Apple\Domain\AppleState;
use yii\db\ActiveRecord;

/**
 * Apple ActiveRecord model
 *
 * @property int|string|null $id
 * @property int|string|null $color
 * @property int|string|null $created_at
 * @property int|string|null $fell_at
 * @property int|string|null $state
 * @property float|string|null $size
 */
class AppleAR extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        // Здесь можно было бы реализовать карсивые кастомные валидаторы на основе доменных VO,
        // но у нас нет "формы" для мутации или создания яблока что бы интерактивно это отображать,
        // на данном этапе не целесообразно. А так, вся валидация в домене
        return [

        ];
    }

    public function init(): void
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->color = array_rand(array_keys(self::availableColors()));
            $this->created_at = time();
            $this->state = AppleState::ON_TREE->value();
            $this->size = 1.0;
        }
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->makeEntity();

        return true;
    }

    public function fallToGround(): void
    {
        $this->fromEntity(
            $this->makeEntity()->fallToGround()
        )->save(false);
    }

    public function eat(int $percent): bool
    {
        $this->fromEntity(
            $entity = $this->makeEntity()->eat(Percentage::of($percent))
        );
        if ($entity->toDeleteCrutch()) {
            $this->delete();
            return false;
        }

        $this->save(false);
        return true;
    }

    public function statusLabel(): string
    {
        $this->isRotten();
        return self::availableStates()[$this->state] ?? (string) $this->state;
    }

    public function colorLabel(): string
    {
        return self::availableColors()[$this->color] ?? (string) $this->color;
    }

    public function isRotten(): bool
    {
        $rotten = $this->state === AppleState::ROTTEN->value()
            || ($this->fell_at && $this->secondsTillRot() < 1)
        ;
        if ($rotten) {
            $this->state = AppleState::ROTTEN->value();
        }
        return $rotten;
    }

    public function isOnTree(): bool
    {
        return $this->state === AppleState::ON_TREE->value();
    }

    public function isFallen(): bool
    {
        return $this->state === AppleState::FALLEN->value();
    }

    public static function availableColors(): array
    {
        return [
            AppleColor::RED->value() => 'Красное',
            AppleColor::GREEN->value() => 'Зеленое',
            AppleColor::YELLOW->value() => 'Желтое',
            AppleColor::PINK->value() => 'Розовое',
        ];
    }

    public static function availableStates(): array
    {
        return [
            AppleState::ON_TREE->value() => 'На дереве',
            AppleState::FALLEN->value() => 'Упало',
            AppleState::ROTTEN->value() => 'Сгнило',
        ];
    }

    private function makeEntity(): Apple
    {
        return Apple::create(
            id: $this->id,
            state: $this->state,
            size: $this->size,
            color: $this->color,
            createdAt: $this->created_at,
            fellAt: $this->fell_at,
            timeToRot: \Yii::$app->params[PARAM_APPLE_SECOND_TILL_ROT]
        );
    }

    private function fromEntity(Apple $entity): self
    {
        $this->id = $entity->id()?->value();
        $this->state = $entity->state()->value();
        $this->size = $entity->size()->value();
        $this->color = $entity->color()->value();
        $this->created_at = $entity->createdAt()->value();
        $this->fell_at = $entity->fellAt()?->value();

        return $this;
    }

    public function eatenPercent(): int
    {
        return 100 - (int) ((float) $this->size * 100);
    }

    public function secondsTillRot(): int
    {
        return max(0, \Yii::$app->params[PARAM_APPLE_SECOND_TILL_ROT] - (time() - $this->fell_at));
    }
}
