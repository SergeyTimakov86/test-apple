<?php

declare(strict_types=1);

namespace frontend\controllers\Apple;

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
     * @throws NotFoundHttpException
     */
    public function run($id): Response
    {
        $apple = $this->getApple((int) $id);

        try {
            $apple->delete();
            $this->flashSuccess('Яблоко удалено');
        } catch (\Exception $e) {
            // TODO: some logging
            $this->flashError('Ошибка при удалении яблока');
        }

        return $this->redirectToUrl(ListAction::url());
    }
}
