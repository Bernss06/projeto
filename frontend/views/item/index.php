<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Colecao $colecao */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Itens da Coleção';
$this->params['breadcrumbs'][] = ['label' => 'Minhas Coleções', 'url' => ['site/dashboard']];
$this->params['breadcrumbs'][] = ['label' => $colecao->nome, 'url' => ['colecao/view', 'id' => $colecao->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="item-index container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="fw-bold text-gradient2 mb-1"><?= Html::encode($this->title) ?></h1>
                    <p class="text-secondary mb-0">Coleção: <?= Html::encode($colecao->nome) ?></p>
                </div>
                <div class="d-flex gap-2">
                    <?= Html::a('Voltar à Coleção', ['colecao/view', 'id' => $colecao->id], ['class' => 'btn btn-dark-alt']) ?>
                    <?php if ($colecao->canEdit()): ?>
                        <?= Html::a('Adicionar Item', ['create', 'colecaoId' => $colecao->id], ['class' => 'btn btn-gradient']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'summary' => false,
                        'options' => ['class' => 'table-responsive'],
                        'tableOptions' => ['class' => 'table table-hover mb-0 align-middle text-light'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'nome',
                            'descricao',
                            'nota',
                            [
                                'attribute' => 'dtaquisicao',
                                'format' => ['date', 'php:d/m/Y'],
                                'label' => 'Aquisição',
                            ],
                            'categoria_id',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {update} {delete}',
                                'visibleButtons' => [
                                    'update' => static fn($item) => $colecao->canEdit(),
                                    'delete' => static fn($item) => $colecao->canEdit(),
                                ],
                                'urlCreator' => static function ($action, $item) {
                                    return Url::to(['item/' . $action, 'id' => $item->id]);
                                },
                            ],
                        ],
                        'emptyText' => '<div class="p-4 text-center text-secondary">Ainda não existem itens nesta coleção.</div>',
                    ]) ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

