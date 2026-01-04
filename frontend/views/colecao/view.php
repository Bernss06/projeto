<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Colecao $model */
/** @var common\models\Item[] $items */
/** @var bool $isFavorited */

$this->title = $model->nome;
?>

<div class="colecao-view container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
                <div>
                    <h1 class="fw-bold text-gradient2 mb-1"><?= Html::encode($this->title) ?></h1>
                    <p class="text-secondary mb-0">
                        <?= $model->isPublic() ? 'Coleção pública' : 'Coleção privada' ?> &middot;
                        <?= Html::encode(Yii::$app->formatter->asDatetime($model->updated_at, 'php:d M Y \\à\\s H:i')) ?>
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <?php if ($model->canEdit()): ?>
                        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-gradient']) ?>
                        <?= Html::a('Apagar', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-outline-danger',
                            'data' => [
                                'confirm' => 'Tem a certeza que quer apagar esta coleção?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>

                    <?php if (!$model->canEdit() && !Yii::$app->user->isGuest && $model->isPublic()): ?>
                        <?php if ($isFavorited): ?>
                            <?= Html::beginForm(['colecao/unfavorite', 'id' => $model->id], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::submitButton('Remover dos Favoritos', ['class' => 'btn btn-outline-danger']) ?>
                            <?= Html::endForm() ?>
                        <?php else: ?>
                            <?= Html::beginForm(['colecao/favorite', 'id' => $model->id], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::submitButton('Adicionar aos Favoritos', ['class' => 'btn btn-outline-light']) ?>
                            <?= Html::endForm() ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?= Html::a('Voltar', [$model->canEdit() ? 'site/dashboard' : 'colecao/index'], ['class' => 'btn btn-dark-alt']) ?>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h4 fw-bold text-light mb-0">Itens da Coleção</h2>
                <?php if ($model->canEdit()): ?>
                    <?= Html::a('Adicionar Item', ['item/create', 'colecaoId' => $model->id], ['class' => 'btn btn-gradient']) ?>
                <?php endif; ?>
            </div>

            <?php if (empty($items)): ?>
                <div class="text-center text-secondary py-5">
                    Ainda não existem itens nesta coleção.
                </div>
            <?php else: ?>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($items as $item): ?>
                        <div class="col">
                            <div class="card item-card h-100 border-0 shadow-sm rounded-4 bg-dark bg-opacity-75 overflow-hidden">

                                <div class="ratio ratio-16x9 bg-black">
                                    <img src="<?= Html::encode($item->getImagemUrl()) ?>"
                                         alt="<?= Html::encode($item->nome) ?>"
                                         class="w-100 h-100 object-fit-cover">
                                </div>

                                <div class="card-body">
                                    <h5 class="text-light mb-1"><?= Html::encode($item->nome) ?></h5>

                                    <span class="badge bg-gradient text-uppercase small">
                                        <?= Html::encode($item->getCategoriaNome()) ?>
                                    </span>

                                    <?php if ($item->descricao): ?>
                                        <p class="text-secondary small mt-2">
                                            <?= Html::encode($item->descricao) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="card-footer border-0 bg-transparent d-flex gap-2 flex-wrap">

                                    <?= Html::a('Ver', ['item/view', 'id' => $item->id], [
                                        'class' => 'btn btn-sm btn-outline-light flex-grow-1'
                                    ]) ?>

                                    <?php if (!$model->canEdit() && !Yii::$app->user->isGuest && $model->isPublic()): ?>
                                        <?= Html::a('Trocar', ['troca/create', 'item_id' => $item->id], [
                                            'class' => 'btn btn-sm btn-gradient flex-grow-1'
                                        ]) ?>
                                    <?php endif; ?>

                                    <?php if ($model->canEdit()): ?>
                                        <?= Html::a('Editar', ['item/update', 'id' => $item->id], [
                                            'class' => 'btn btn-sm btn-gradient flex-grow-1'
                                        ]) ?>
                                        <?= Html::a('Apagar', ['item/delete', 'id' => $item->id], [
                                            'class' => 'btn btn-sm btn-outline-danger flex-grow-1',
                                            'data' => [
                                                'confirm' => 'Tem a certeza?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>
