<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Congratulations!</h1>
            <p class="fs-5 fw-light">Здесь вы можете проверить тестовое задание. Правда для этого нужно аутентифицироваться.</p>
            <p><?= Html::a('Поехали', ['/site/login'],['class' => ['btn btn-lg btn-success']]) ?></p>
        </div>
    </div>
</div>
