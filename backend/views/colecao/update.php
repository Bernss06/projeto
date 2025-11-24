<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Colecao $model */

$this->title = 'Update Colecao: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Colecaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="colecao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
