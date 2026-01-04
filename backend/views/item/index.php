<?php

use common\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Itens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nome',
            [
                'label' => 'Item',
                'format' => 'html',
                'value' => function($model) {
                    return Html::img($model->getImagemUrl(), ['width' => '100', 'height' => '100', 'style' => 'object-fit: cover;']);
                },
            ],
            [
                'attribute' => 'colecao_id',
                'label' => 'Coleção',
                'value' => 'colecao.nome',
            ],
            [
                'label' => 'Dono da Coleção',
                'value' => 'colecao.user.username',
            ],
            'categoria_id',
            //'descricao',
            //'nota',
            //'dtaquisicao',
            //'nome_foto',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}', // Explicitly remove create/other actions if any default
                'urlCreator' => function ($action, Item $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
