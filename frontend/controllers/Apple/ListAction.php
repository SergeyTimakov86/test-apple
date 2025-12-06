<?php

namespace frontend\controllers\Apple;

use frontend\models\Apple\AppleAR;

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
        ]);
    }
}
