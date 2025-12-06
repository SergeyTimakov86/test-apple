<?php

declare(strict_types=1);

namespace frontend\controllers\Apple;

use yii\web\NotFoundHttpException;
use yii\web\Response;

final class FallAction extends BaseAction
{
    public static function route(): string
    {
        return 'apple/fall';
    }

    /**
     * @throws \Throwable
     * @throws NotFoundHttpException
     */
    public function run($id): Response
    {
        $apple = $this->getApple((int) $id);

        try {
            $apple->fallToGround();
            $this->flashSuccess('Яблоко упало на землю');
        } catch (\Throwable $e) {
            // TODO: some logging
            $this->flashError($e->getMessage());
        }

        return $this->redirectToUrl(ListAction::url());
    }
}
