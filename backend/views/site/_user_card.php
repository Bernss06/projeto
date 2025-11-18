<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\User $model */
?>

<div class="card h-100 shadow-sm border-0">
    <div class="card-body text-center">
        <div class="mb-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 64px; height: 64px; font-size: 24px;">
                <?= strtoupper(substr($model->username, 0, 1)) ?>
            </div>
        </div>
        
        <h5 class="card-title text-dark font-weight-bold mb-0">
            <?= Html::encode($model->username) ?>
        </h5>
        <p class="text-muted small mb-2"><?= Html::encode($model->email) ?></p>
        
        <span class="badge badge-light border">
            ID: <?= $model->id ?>
        </span>
        <span class="badge <?= $model->status == 10 ? 'badge-success' : 'badge-secondary' ?>">
            <?= $model->status == 10 ? 'Ativo' : 'Inativo' ?>
        </span>
    </div>

    <div class="card-footer bg-white border-top-0 text-center pb-4">
        <div class="btn-group" role="group">
            <a href="<?= Url::to(['/user/view', 'id' => $model->id]) ?>" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye"></i> Ver
            </a>
            
            <a href="<?= Url::to(['/user/update', 'id' => $model->id]) ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
</div>