<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Item $model */
/** @var common\models\Colecao $colecao */

$this->title = 'Editar Item';
$this->params['breadcrumbs'][] = ['label' => 'Minhas Coleções', 'url' => ['colecao/mine']];
$this->params['breadcrumbs'][] = ['label' => $colecao->nome, 'url' => ['colecao/view', 'id' => $colecao->id]];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['item/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>

<div class="item-update form-page d-flex flex-column align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="text-center mb-4">
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
                    <h1 class="fw-bold text-gradient2 mb-1"><?= Html::encode($this->title) ?></h1>
                    <p class="text-secondary mb-0">Atualize as informações do item "<?= Html::encode($model->nome) ?>"</p>
                </div>

                <div class="form-card">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'colecao' => $colecao,
                        'categories' => $categories,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

