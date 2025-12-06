<?php

declare(strict_types=1);

namespace frontend\controllers\Apple;

use frontend\models\Apple\AppleAR;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class GenerateAction extends BaseAction
{
    public static function route(): string
    {
        return 'apple/generate';
    }

    /**
     * @throws \Throwable
     */
    public function run(): Response
    {
        $count = random_int(1, 5);

        try {
            for ($i = 0; $i < $count; $i++) {
                $apple = new AppleAR();
                if (!$apple->save()) {
                    throw new \RuntimeException();
                }
            }

            $this->flashSuccess("Создано яблок: {$count}");
        } catch (\Throwable $e) {
            // TODO: some logging
            $this->flashError('Ошибка при создании яблок');
        }

        return $this->redirectToUrl(ListAction::url());
    }
}
