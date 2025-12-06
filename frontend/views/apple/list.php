<?php

use frontend\controllers\Apple\DeleteAction;
use frontend\controllers\Apple\EatAction;
use frontend\controllers\Apple\FallAction;
use frontend\controllers\Apple\GenerateAction;
use frontend\models\Apple\AppleAR;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var AppleAR[] $apples */

$this->title = 'Яблочный сад';

?>

<div class="apple-index">

    <div class="mb-4">
        <?= Html::beginForm(GenerateAction::url(), 'post', ['class' => 'd-inline']) ?>
            <?= Html::submitButton('Сгенерировать яблоки', ['class' => 'btn btn-success btn-lg']) ?>
        <?= Html::endForm() ?>
    </div>

    <?php if (empty($apples)): ?>
        <div class="alert alert-info">
            Яблок пока что нет. Но можно сгенерировать.
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($apples as $apple): ?>
                <div class="list-group-item <?= $apple->isRotten() ? 'list-group-item-danger' : ($apple->state === 0 ? 'list-group-item-success' : 'list-group-item-warning') ?>">
                    <div class="row align-items-center">

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="mb-0">🍎 #<?= $apple->id ?></h5>
                                <span class="badge bg-secondary"><?= $apple->getColorLabel() ?></span>
                                <span class="badge <?= $apple->isRotten() ? 'bg-danger' : ($apple->state === 0 ? 'bg-success' : 'bg-warning text-dark') ?>">
                                    <?= $apple->getStatusLabel() ?>
                                </span>
                            </div>

                            <div class="mt-2 small text-muted">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <strong>Появилось:</strong> <?= date('d.m.Y H:i', $apple->created_at) ?>
                                    </div>
                                    <?php if ($apple->fell_at): ?>
                                        <div class="col-auto">
                                            <strong>Упало:</strong> <?= date('d.m.Y H:i', $apple->fell_at) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($apple->fell_at && !$apple->isRotten()): ?>
                                        <?php
                                        $timeLeft = AppleAR::TIME_TO_ROT - (time() - $apple->fell_at);
                                        $hoursLeft = floor($timeLeft / 3600);
                                        $minutesLeft = floor(($timeLeft % 3600) / 60);
                                        ?>
                                        <div class="col-auto text-danger">
                                            <strong>До гниения:</strong> <?= $hoursLeft ?>ч <?= $minutesLeft ?>м
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">Съедено:</small>
                                    <div class="progress flex-grow-1" style="height: 18px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             style="width: <?= $apple->eaten_percent ?>%"
                                             aria-valuenow="<?= $apple->eaten_percent ?>"
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?= number_format($apple->eaten_percent, 0) ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex gap-2 justify-content-end flex-wrap">

                                <?= Html::beginForm([FallAction::route(), 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                                    <?= Html::submitButton('⬇️ Уронить', ['class' => 'btn btn-warning btn-sm', 'data-confirm' => 'Уронить яблоко принудительно?']) ?>
                                <?= Html::endForm() ?>

                                <?= Html::beginForm([EatAction::route(), 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                                    <div class="input-group input-group-sm" style="width: 100px;">
                                        <?= Html::input('number', 'percent', 25, [
                                            'class' => 'form-control',
                                            'min' => 1,
                                            'max' => 100,
                                            'step' => 1,
                                            'placeholder' => '%'
                                        ]) ?>
                                        <?= Html::submitButton('🍴', ['class' => 'btn btn-primary', 'data-confirm' => 'Откусить?', 'title' => 'Откусить']) ?>
                                    </div>
                                <?= Html::endForm() ?>

                                <?= Html::beginForm([DeleteAction::route(), 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                                    <?= Html::submitButton('🗑️', ['class' => 'btn btn-danger btn-sm', 'data-confirm' => 'Удалить яблоко?', 'title' => 'Удалить']) ?>
                                <?= Html::endForm() ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

