<?php

namespace frontend\controllers\Apple;

use frontend\models\Apple\AppleAR;
use frontend\models\Apple\Domain\AppleState;

final class ListAction extends BaseAction
{
    public static function route(): string
    {
        return 'apple/list';
    }

    public function run(): string
    {
        $apples = AppleAR::find()->orderBy('id DESC')->all();

        return $this->controller->render('list', [
            'apples' => $apples,
            'secondsToRot' => \Yii::$app->params[PARAM_APPLE_SECOND_TILL_ROT],
            'rottenStateLabel' => AppleAR::availableStates()[($s = AppleState::ROTTEN)->value()] ?? $s->name,
        ]);
    }
}
