<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Colecao $model */

$this->title = 'Criar Nova Coleção';
$this->params['breadcrumbs'][] = ['label' => 'Minhas Coleções', 'url' => ['mine']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="colecao-create form-page flex-column gap-4">
    <div class="text-center">
        <div class="login-logo-icon mx-auto mb-3">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="3" width="5" height="5" fill="white"/>
                <rect x="10" y="3" width="5" height="5" fill="white"/>
                <rect x="17" y="3" width="5" height="5" fill="white"/>
                <rect x="3" y="10" width="5" height="5" fill="white"/>
                <rect x="10" y="10" width="5" height="5" fill="white"/>
                <rect x="17" y="10" width="5" height="5" fill="white"/>
                <rect x="3" y="17" width="5" height="5" fill="white"/>
                <rect x="10" y="17" width="5" height="5" fill="white"/>
                <rect x="17" y="17" width="5" height="5" fill="white"/>
            </svg>
        </div>
        <h1 class="fw-bold text-gradient2 mb-2"><?= Html::encode($this->title) ?></h1>
        <p class="text-secondary mb-0">
            Dê um nome, descreva a sua coleção e escolha se quer partilhá-la com a comunidade.
        </p>
    </div>

    <div class="form-card">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
