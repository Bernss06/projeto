<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Update User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <div class="text-center mb-4">
        <?= Html::img($model->getProfilePictureUrl(), [
            'class' => 'img-circle elevation-2', 
            'style' => 'width: 120px; height: 120px; object-fit: cover;',
            'alt' => 'User Image'
        ]) ?>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
