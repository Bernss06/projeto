<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Troca[] $trocas */

$this->title = 'Histórico de Trocas';
?>

<div class="dashboard-page py-5">

    <header class="dashboard-header d-flex justify-content-between align-items-center mb-5 px-4 px-md-5">

        <div class="d-flex align-items-center gap-3">
            <div class="logo-icon d-flex align-items-center justify-content-center">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <h2 class="fw-bold text-gradient2 mb-0">Histórico de Trocas</h2>
        </div>

        <div>
            <a href="<?= Url::to(['site/dashboard']) ?>" class="btn btn-outline-light px-4 py-2 fw-semibold">
                <i class="bi bi-arrow-left me-2"></i> Voltar ao Dashboard
            </a>
        </div>

    </header>

    <main class="container">

        <h3 class="fw-bold mb-4 text-light text-center">As Suas Trocas Realizadas</h3>

        <?php if (empty($trocas)): ?>

            <div class="text-center text-light text-opacity-75 mt-5">
                <i class="bi bi-hourglass-split display-4 mb-3"></i>
                <p class="fs-4 mb-1">Ainda não realizou trocas.</p>
                <p>Comece por explorar coleções e procurar oportunidades de troca.</p>
            </div>

        <?php else: ?>

            <div class="d-flex flex-column gap-4">

                <?php foreach ($trocas as $troca): ?>
                    <?php 
                        $isRequester = $troca->user_id === Yii::$app->user->id;
                        // $isOwner = !$isRequester; 
                        $otherUser = $isRequester ? $troca->proprietarioItem : $troca->user;
                        $item = $troca->item;
                    ?>
                    <div class="card bg-dark border-secondary rounded-4 shadow-sm hover-shadow px-3 py-4"
                         style="border-color: rgba(142,45,226,0.25) !important;">

                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">

                            <div>
                                <span class="badge bg-gradient text-uppercase mb-2 px-3 py-2 rounded-pill"
                                      style="background: linear-gradient(135deg, #8e2de2, #4a00e0); font-size: 0.75rem;">
                                    Pedido Nº <?= $troca->id ?>
                                </span>

                                <h5 class="fw-bold text-light mb-1">
                                    <?php if ($isRequester): ?>
                                        Pediu <span class="text-gradient2"><?= Html::encode($item->nome ?? '—') ?></span>
                                    <?php else: ?>
                                        Pedido de <span class="text-gradient2"><?= Html::encode($item->nome ?? '—') ?></span>
                                    <?php endif; ?>
                                </h5>

                                <p class="text-secondary mb-1">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <?= Yii::$app->formatter->asDate($troca->created_at) ?>
                                </p>

                                <?php if ($otherUser): ?>
                                    <p class="text-secondary mb-1">
                                        <i class="bi bi-person-gear me-2"></i>
                                        <?php if ($isRequester): ?>
                                            A: <span class="text-light fw-semibold"><?= Html::encode($otherUser->username) ?></span>
                                        <?php else: ?>
                                            De: <span class="text-light fw-semibold"><?= Html::encode($otherUser->username) ?></span>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex flex-column text-end">

                                <?php 
                                    $statusLabel = $troca->getStatusLabel();
                                    $badgeClass = 'bg-warning';
                                    if ($troca->estado == \common\models\Troca::STATUS_ACEITE) $badgeClass = 'bg-success';
                                    if ($troca->estado == \common\models\Troca::STATUS_RECUSADA) $badgeClass = 'bg-danger';
                                ?>
                                <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill mb-2 text-uppercase fw-semibold">
                                    <?= Html::encode($statusLabel) ?>
                                </span>

                                <a href="<?= Url::to(['troca/view', 'id' => $troca->id]) ?>"
                                   class="btn btn-sm btn-gradient text-uppercase fw-semibold mt-2"
                                   style="font-size: 0.75rem; padding: 0.4rem 0.8rem;">
                                    Ver detalhes
                                </a>

                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </main>

</div>
