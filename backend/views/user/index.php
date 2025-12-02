<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            [
                'label' => 'Profile Picture',
                'format' => 'html',
                'value' => function($model) {
                    /** @var \common\models\User $model */
                    return Html::img($model->getProfilePictureUrl(), ['width' => '80', 'height' => '80', 'class' => 'img-circle', 'style' => 'object-fit: cover;']);
                },
            ],
            'email:email',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    switch($model->status) {
                        case User::STATUS_ACTIVE:
                            return 'Active';
                        case User::STATUS_INACTIVE:
                            return 'Inactive';
                        case User::STATUS_DELETED:
                            return 'Deleted';
                        default:
                            return 'Unknown';
                    }
                },
                'filter' => [
                    User::STATUS_ACTIVE => 'Active',
                    User::STATUS_INACTIVE => 'Inactive',
                    User::STATUS_DELETED => 'Deleted',
                ],
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                },
                'filter' => false, // Disable filter for this column
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->updated_at);
                },
                'filter' => false, // Disable filter for this column
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
