<?php

use frontend\controllers\Apple\DeleteAction;
use frontend\controllers\Apple\EatAction;
use frontend\controllers\Apple\FallAction;
use frontend\controllers\Apple\GenerateAction;
use frontend\controllers\Apple\ListAction;
use frontend\models\Apple\AppleAR;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var AppleAR[] $apples */
/** @var string $secondsToRot */
/** @var string $rottenStateLabel */

$this->title = 'Яблочный сад';

?>

<div class="apple-list">
    <div class="mb-4">
        <?= Html::beginForm([GenerateAction::route()], 'post', ['class' => 'd-inline']) ?>
            <?= Html::submitButton('🌳 Сгенерировать яблоки', ['class' => 'btn btn-success']) ?>
        <?= Html::endForm() ?>

        <?= Html::a('🔄 Обновить', [ListAction::route()], ['class' => 'btn btn-secondary ms-2']) ?>

        <span class="ms-3 text-muted">Всего яблок: <strong><?= count($apples) ?></strong></span>
    </div>

    <?php if (empty($apples)): ?>
        <div class="alert alert-info">
            Яблок пока-что нет. Но можно сгенерировать.
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($apples as $apple): ?>
                <div class="list-group-item <?= $apple->isRotten() ? 'list-group-item-danger' : ($apple->isOnTree() ? 'list-group-item-success' : 'list-group-item-warning') ?>">
                    <div class="row align-items-center">

                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="mb-0">🍎 #<?= $apple->id ?></h5>
                                <span>Размер: <?= $apple->size ?></span>
                                <span class="badge bg-secondary"><?= $apple->colorLabel() ?></span>
                                <span class="badge <?= $apple->isRotten() ? 'bg-danger' : ($apple->isOnTree() ? 'bg-success' : 'bg-warning text-dark') ?>">
                                    <?= $apple->statusLabel() ?>
                                </span>
                            </div>

                            <div class="mt-2 small text-muted">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <strong>Появилось:</strong> <?= date('d/m H:i:s', $apple->created_at) ?>
                                    </div>
                                    <?php if ($apple->fell_at): ?>
                                        <div class="col-auto">
                                            <strong>Упало:</strong> <?= date('d/m H:i:s', $apple->fell_at) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($apple->isFallen() && !$apple->isRotten()): ?>
                                        <div class="col-auto text-danger"
                                             data-fell-at="<?= $apple->fell_at ?>"
                                             data-apple-id="<?= $apple->id ?>">
                                            <strong>До гниения:</strong> <span class="timer-seconds"><?= $apple->secondsTillRot() ?></span> сек.
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">Съедено:</small>
                                    <div class="progress flex-grow-1" style="height: 18px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             style="width: <?= $apple->eatenPercent() ?>%"
                                             aria-valuenow="<?= $apple->eatenPercent() ?>"
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?= number_format($apple->eatenPercent()) ?>%
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

<?php
// TODO: to assets
$this->registerJs(<<<JS

setInterval(function() {
    document.querySelectorAll('[data-fell-at]').forEach(function(element) {
        const fellAt = parseInt(element.dataset.fellAt);
        const now = Math.floor(Date.now() / 1000);
        const timeToRot = {$secondsToRot};
        
        const secondsLeft = timeToRot - (now - fellAt);
        
        if (secondsLeft <= 0) {
            const listItem = element.closest('.list-group-item');
            
            listItem.classList.remove('list-group-item-warning');
            listItem.classList.add('list-group-item-danger');
            
            const statusBadge = listItem.querySelector('.badge.bg-warning, .badge.text-dark');
            if (statusBadge) {
                statusBadge.classList.remove('bg-warning', 'text-dark');
                statusBadge.classList.add('bg-danger');
                statusBadge.textContent = '{$rottenStateLabel}';
            }
            
            element.style.display = 'none';

        } else {
            const timerSpan = element.querySelector('.timer-seconds');
            if (timerSpan) {
                timerSpan.textContent = secondsLeft;
            }
        }
    });
}, 333);
JS
);
?>
