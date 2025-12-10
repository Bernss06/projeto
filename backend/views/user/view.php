<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        
        <?php if ($model->status == \common\models\User::STATUS_ACTIVE): ?>
            <?= Html::a('Deactivate', ['deactivate', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Are you sure you want to deactivate this user?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Soft Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to soft delete this user? The user will not be able to login.',
                    'method' => 'post',
                ],
            ]) ?>
        <?php elseif ($model->status == \common\models\User::STATUS_INACTIVE): ?>
            <?= Html::a('Activate', ['activate', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Are you sure you want to activate this user?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Soft Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to soft delete this user? The user will not be able to login.',
                    'method' => 'post',
                ],
            ]) ?>
        <?php elseif ($model->status == \common\models\User::STATUS_DELETED): ?>
            <?= Html::a('Activate', ['activate', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Are you sure you want to reactivate this user?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    switch($model->status) {
                        case \common\models\User::STATUS_ACTIVE:
                            return 'Active';
                        case \common\models\User::STATUS_INACTIVE:
                            return 'Inactive';
                        case \common\models\User::STATUS_DELETED:
                            return 'Deleted';
                        default:
                            return 'Unknown';
                    }
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'value' => date('Y-m-d H:i:s', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'value' => date('Y-m-d H:i:s', $model->updated_at),
            ],
        ],
    ]) ?>

</div>
