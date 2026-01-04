<?php
use yii\helpers\Html;

/** @var common\models\Troca $troca */

$this->title = "Troca Nº {$troca->id}";
?>
<div class="container py-5">

    <h1 class="fw-bold mb-4"><?= Html::encode($this->title) ?></h1>

    <!-- Itens enviados e recebidos -->
    <p class="fs-5">
        <?= Html::encode($troca->itemEnviado->nome ?? '—') ?>
        <i class="bi bi-arrow-left-right mx-2"></i>
        <?= Html::encode($troca->itemRecebido->nome ?? '—') ?>
    </p>

    <!-- Status -->
    <p>
        <strong>Status:</strong>
        <span class="badge bg-<?=
        $troca->status === 'pendente' ? 'warning' :
            ($troca->status === 'concluída' ? 'success' : 'danger') ?>">
            <?= Html::encode($troca->status) ?>
        </span>
    </p>

    <!-- Data -->
    <p>
        <strong>Data:</strong>
        <?= Yii::$app->formatter->asDatetime($troca->data_troca) ?>
    </p>

    <!-- Parceiro ou quem recusou -->
    <p>
        <strong>
            <?php
            if ($troca->status === 'pendente') {
                echo 'Pedido enviado a:';
            } elseif ($troca->status === 'recusada') {
                echo 'Recusado por:';
            } else { // concluída
                echo 'Com quem trocou:';
            }
            ?>
        </strong>
        <span class="text-light fw-semibold">
            <?php
            if ($troca->status === 'recusada') {
                echo Html::encode($troca->recusouUsername ?? '—');
            } else {
                echo Html::encode($troca->parceiroUsername ?? '—');
            }
            ?>
        </span>
    </p>

    <hr>

    <!-- Botões Aceitar / Recusar (apenas para trocas pendentes onde o utilizador é o destino) -->
    <?php if ($troca->status === 'pendente' && $troca->user_destino_id === Yii::$app->user->id): ?>
        <div class="d-flex gap-2 mt-3">
            <?= Html::a(
                'Aceitar',
                ['troca/aceitar', 'id' => $troca->id],
                [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post',
                        'confirm' => 'Tem a certeza que quer aceitar esta troca?',
                    ],
                ]
            ) ?>

            <?= Html::a(
                'Recusar',
                ['troca/recusar', 'id' => $troca->id],
                [
                    'class' => 'btn btn-outline-danger',
                    'data' => [
                        'method' => 'post',
                        'confirm' => 'Tem a certeza que quer recusar esta troca?',
                    ],
                ]
            ) ?>
        </div>
    <?php endif; ?>

    <!-- Botão voltar -->
    <div class="mt-4">
        <?= Html::a(
            'Voltar ao Histórico',
            ['site/historicotrocas'],
            ['class' => 'btn btn-secondary']
        ) ?>
    </div>

</div>
