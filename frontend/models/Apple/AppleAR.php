<?php

namespace frontend\models\Apple;

use yii\db\ActiveRecord;

/**
 * Apple ActiveRecord model
 *
 * @property int $id
 * @property int $color
 * @property int $created_at
 * @property int|null $fell_at
 * @property int $state
 * @property int $eaten_percent
 */
class AppleAR extends ActiveRecord
{
    // секунд до гниения
    const TIME_TO_ROT = 120;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['color', 'created_at', 'state', 'eaten_percent'], 'required'],
            [['color', 'state', 'eaten_percent', 'created_at', 'fell_at'], 'integer'],
            [['state'], 'in', 'range' => [0, 1, 2,]],
            [['eaten_percent'], 'default', 'value' => 0],
            [['state'], 'default', 'value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Цвет',
            'created_at' => 'Дата появления',
            'fell_at' => 'Дата падения',
            'state' => 'Статус',
            'eaten_percent' => 'Съедено (%)',
        ];
    }

    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            // init defaults
            $this->color = 0;
            $this->created_at = random_int(time() - 30 * 24 * 3600, time());
            $this->state = 0;
            $this->eaten_percent = 0;
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        return true;
    }

    public function fallToGround(): void
    {
        if ($this->state !== 0) {
            throw new \Exception('Яблоко уже упало с дерева');
        }

        $this->state = 1;
        $this->fell_at = time();

        if (!$this->save(false)) {
            throw new \Exception('Не удалось сохранить изменения');
        }
    }

    public function eat(int $percent): void
    {
        //$this->checkIfRotten();

        if ($this->state === 0) {
            throw new \Exception('Съесть нельзя, яблоко на дереве');
        }

        if ($this->state === 2) {
            throw new \Exception('Съесть нельзя, яблоко испорчено');
        }

        if ($percent <= 0 || $percent > 100) {
            throw new \Exception('Процент должен быть от 0 до 100');
        }

        if ($this->eaten_percent >= 100) {
            throw new \Exception('Яблоко уже полностью съедено');
        }

        $this->eaten_percent += $percent;
        if ($this->eaten_percent > 100) {
            $this->eaten_percent = 100;
        }

        if (!$this->save(false)) {
            throw new \Exception('Не удалось сохранить изменения');
        }

        if ($this->eaten_percent >= 100) {
            $this->delete();
        }
    }

    public function getStatusLabel(): string
    {
        $statuses = [
            0 => 'На дереве',
            1 => 'Упало',
            2 => 'Сгнило',
        ];

        return $statuses[$this->state] ?? (string) $this->state;
    }

    public function getColorLabel(): string
    {
        return self::getAvailableColors()[$this->color] ?? (string) $this->color;
    }

    public function isRotten(): bool
    {
        return $this->state === 2;
    }

    public static function getAvailableColors(): array
    {
        return [
            0 => 'Красное',
            1 => 'Зеленое',
            2 => 'Желтое',
            3 => 'Розовое',
        ];
    }
}
