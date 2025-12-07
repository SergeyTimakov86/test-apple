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
     */
    public function run($id): Response
    {
        $apple = $this->getApple((int) $id);
        $percent = (int) Yii::$app->request->post('percent', 0);

        try {
            if (!$apple->eat($percent)) {
                $this->flashSuccess('Яблоко полностью съедено и, как следствие, удалено');
            } else {
                $this->flashSuccess("Откушено {$percent}% яблока");
            }
        } catch (\DomainException $e) { // vvv TODO: get rid of these via custom error handler
            $this->flashError($e->getMessage());
        } catch (\Throwable $e) {
            Yii::error($e->getMessage());
            $this->flashError('Something went wrong.');
        }

        return $this->redirectToUrl(ListAction::url());
    }
}
