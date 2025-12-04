<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Item $model */

$this->title = $model->nome;
$colecao = $model->colecao;
$this->params['breadcrumbs'][] = ['label' => 'Minhas Coleções', 'url' => ['site/dashboard']];
$this->params['breadcrumbs'][] = ['label' => $colecao->nome, 'url' => ['colecao/view', 'id' => $colecao->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="item-view container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="fw-bold text-gradient2 mb-1"><?= Html::encode($this->title) ?></h1>
                    <p class="text-secondary mb-0">Coleção: <?= Html::a(Html::encode($colecao->nome), ['colecao/view', 'id' => $colecao->id], ['class' => 'text-gradient2 text-decoration-none']) ?></p>
                </div>
                <div class="d-flex gap-2">
                    <?php if ($colecao->canEdit()): ?>
                        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-gradient']) ?>
                        <?= Html::a('Apagar', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-outline-danger',
                            'data' => [
                                'confirm' => 'Tem a certeza que quer apagar este item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>
                    <?= Html::a('Voltar', ['colecao/view', 'id' => $colecao->id], ['class' => 'btn btn-dark-alt']) ?>
                </div>
            </div>

            <div class="form-card">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-borderless mb-0 text-light'],
                    'attributes' => [
                        'id',
                        'nome',
                        'descricao:ntext',
                        'nota:ntext',
                        [
                            'attribute' => 'dtaquisicao',
                            'format' => ['date', 'php:d/m/Y'],
                            'label' => 'Data de Aquisição',
                        ],
                        'nome_foto',
                        'categoria_id',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

