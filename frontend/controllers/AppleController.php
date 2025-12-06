<?php

namespace frontend\controllers;

use frontend\controllers\Apple\DeleteAction;
use frontend\controllers\Apple\EatAction;
use frontend\controllers\Apple\FallAction;
use frontend\controllers\Apple\GenerateAction;
use frontend\controllers\Apple\ListAction;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\filters\AccessControl;

class AppleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                //'only' => ['list'],
                'rules' => [
                    [
                        //'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'fall' => ['POST'],
                    'eat' => ['POST'],
                    'generate' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'list' => ['class' => ListAction::class,],
            'fall' => ['class' => FallAction::class,],
            'eat' => ['class' => EatAction::class,],
            'generate' => ['class' => GenerateAction::class,],
            'delete' => ['class' => DeleteAction::class,],
        ];
    }
}
