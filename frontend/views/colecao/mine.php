<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web.View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Minhas Coleções';
?>
<div class="colecao-mine">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Criar Coleção', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Coleções Públicas', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            [
                'attribute' => 'is_public',
                'label' => 'Pública',
                'value' => static fn($model) => $model->is_public ? 'Sim' : 'Não',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'colecao',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => static fn($model) => $model->canEdit(),
                    'delete' => static fn($model) => $model->canEdit(),
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>


