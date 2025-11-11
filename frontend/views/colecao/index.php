<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Coleções Públicas';
?>
<div class="colecao-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Criar Coleção', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Minhas Coleções', ['mine'], ['class' => 'btn btn-secondary ms-2']) ?>
        <?php else: ?>
            <?= Html::a('Entrar para criar', ['site/login'], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nome',
            [
                'attribute' => 'descricao',
                'format' => 'ntext',
                'contentOptions' => ['style' => 'max-width: 400px; white-space: normal;'],
            ],
            [
                'attribute' => 'is_public',
                'label' => 'Pública',
                'value' => static fn($model) => $model->is_public ? 'Sim' : 'Não',
            ],
            ['class' => 'yii\grid\ActionColumn', 'controller' => 'colecao', 'template' => '{view}'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>


