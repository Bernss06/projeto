<?php
use yii\helpers\Html;

/** @var common\models\Troca $troca */

$this->title = "Troca Nº {$troca->id}";
?>
<div class="container py-5">

    <?php
    $isRequester = $troca->user_id === Yii::$app->user->id;
    $isOwner = $troca->item->colecao->user_id === Yii::$app->user->id;
    $requester = $troca->user;
    $item = $troca->item;
    ?>

    <h1 class="fw-bold mb-4">Pedido Nº <?= $troca->id ?></h1>

    <div class="card bg-dark border-secondary p-4 rounded-4 mb-4">
        <h3 class="h5 text-light mb-3">Detalhes do Pedido</h3>
        
        <div class="mb-3">
            <span class="text-secondary">Item Pedido:</span>
            <div class="d-flex align-items-center gap-3 mt-2">
                <?php if ($item->getImagemUrl()): ?>
                    <img src="<?= Html::encode($item->getImagemUrl()) ?>" alt="<?= Html::encode($item->nome) ?>" 
                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                <?php endif; ?>
                <div>
                    <h4 class="mb-0 fw-bold text-light"><?= Html::encode($item->nome) ?></h4>
                    <span class="badg bg-secondary small"><?= Html::encode($item->colecao->nome ?? 'Coleção') ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <span class="text-secondary">Solicitante:</span>
                <p class="fs-5 fw-semibold text-light mb-0"><?= Html::encode($requester->username) ?></p>
            </div>
            <div class="col-md-6 mb-3">
                <span class="text-secondary">Data do Pedido:</span>
                <p class="fs-5 text-light mb-0"><?= Yii::$app->formatter->asDatetime($troca->created_at) ?></p>
            </div>
        </div>

        <div class="mt-2">
            <span class="text-secondary">Estado Atual:</span>
            <?php 
                $statusLabel = $troca->getStatusLabel();
                $badgeClass = 'bg-warning';
                if ($troca->estado == \common\models\Troca::STATUS_ACEITE) $badgeClass = 'bg-success';
                if ($troca->estado == \common\models\Troca::STATUS_RECUSADA) $badgeClass = 'bg-danger';
            ?>
            <div class="mt-1">
                <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill text-uppercase fw-semibold fs-6">
                    <?= Html::encode($statusLabel) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Actions for Owner -->
    <?php if ($isOwner && $troca->estado == \common\models\Troca::STATUS_PENDENTE): ?>
        <div class="card bg-dark border-secondary p-4 rounded-4 mb-4">
            <h4 class="h5 text-light mb-3">Ações Disponíveis</h4>
            <div class="d-flex gap-3">
                <?= Html::a(
                    '<i class="bi bi-check-lg me-2"></i> Aceitar Pedido',
                    ['troca/aceitar', 'id' => $troca->id],
                    [
                        'class' => 'btn btn-success px-4 fw-semibold',
                        'data' => [
                            'method' => 'post',
                            'confirm' => 'Tem a certeza que aceita este pedido? O item ficará marcado como negociado.',
                        ],
                    ]
                ) ?>

                <?= Html::a(
                    '<i class="bi bi-x-lg me-2"></i> Recusar',
                    ['troca/recusar', 'id' => $troca->id],
                    [
                        'class' => 'btn btn-outline-danger px-4 fw-semibold',
                        'data' => [
                            'method' => 'post',
                            'confirm' => 'Tem a certeza que quer recusar este pedido?',
                        ],
                    ]
                ) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <?= Html::a(
            '<i class="bi bi-arrow-left me-2"></i> Voltar ao Histórico',
            ['site/historicotrocas'],
            ['class' => 'btn btn-outline-secondary px-4']
        ) ?>
    </div>

</div>
