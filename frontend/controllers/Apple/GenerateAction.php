<?php

declare(strict_types=1);

namespace frontend\controllers\Apple;

use frontend\models\Apple\AppleAR;
use Yii;
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
                new AppleAR()->save();
            }

            $this->flashSuccess("Создано яблок: {$count}");
        } catch (\DomainException $e) { // vvv TODO: get rid of these via custom error handler
            $this->flashError($e->getMessage());
        } catch (\Throwable $e) {
            Yii::error($e->getMessage());
            $this->flashError('Something went wrong.');
        }

        return $this->redirectToUrl(ListAction::url());
    }
}
