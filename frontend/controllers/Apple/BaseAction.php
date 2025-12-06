<?php

declare(strict_types=1);

namespace frontend\controllers\Apple;

use frontend\models\Apple\AppleAR;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

abstract class BaseAction extends Action
{
    abstract public static function route(): string;

    public static function url(): string
    {
        static $url;
        return $url ??= Url::toRoute(static::route());
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function getApple(int $id): AppleAR
    {
        if (($model = AppleAR::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Яблоко не найдено');
    }

    protected function redirectToUrl(string $url): Response
    {
        return $this->controller->redirect($url);
    }

    protected function flashSuccess(string $message): void
    {
        \Yii::$app->session->setFlash('success', $message);
    }

    protected function flashError(string $message): void
    {
        \Yii::$app->session->setFlash('error', $message);
    }
}
