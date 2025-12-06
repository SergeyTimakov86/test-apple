<?php

declare(strict_types=1);

namespace frontend\controllers\Apple;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class EatAction extends BaseAction
{
    public static function route(): string
    {
        return 'apple/eat';
    }

    /**
     * @throws \Throwable
     * @throws NotFoundHttpException
     */
    public function run($id): Response
    {
        $apple = $this->getApple((int) $id);
        $percent = (int) Yii::$app->request->post('percent', 0);

        try {
            $apple->eat($percent);

            if ($apple->eaten_percent >= 100) {
                $this->flashSuccess('Яблоко полностью съедено и, как следствие, удалено');
            } else {
                $this->flashSuccess("Откушено {$percent}% яблока");
            }
        } catch (\Throwable $e) {
            $this->flashError($e->getMessage());
        }

        return $this->redirectToUrl(ListAction::url());
    }
}
