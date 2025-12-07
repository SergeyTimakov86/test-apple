<?php

declare(strict_types=1);

namespace frontend\controllers\Apple;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

final class DeleteAction extends BaseAction
{
    public static function route(): string
    {
        return 'apple/delete';
    }

    /**
     * @throws \Throwable
     */
    public function run($id): Response
    {
        $apple = $this->getApple((int) $id);

        try {
            $apple->delete();
            $this->flashSuccess('Яблоко удалено');
        } catch (\DomainException $e) { // vvv TODO: get rid of these via custom error handler
            $this->flashError($e->getMessage());
        } catch (\Throwable $e) {
            Yii::error($e->getMessage());
            $this->flashError('Something went wrong.');
        }

        return $this->redirectToUrl(ListAction::url());
    }
}
