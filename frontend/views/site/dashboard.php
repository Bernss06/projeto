<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Colecao[] $collections */ // <-- Assumindo que o seu model se chama 'Colecao' e vem num array $collections

$this->title = 'Minhas Coleções';
?>

<div class="dashboard-page py-5">

    <header class="dashboard-header d-flex justify-content-between align-items-center mb-5 px-4 px-md-5">
        <div class="d-flex align-items-center gap-3">
            <div class="logo-icon d-flex align-items-center justify-content-center">
                <i class="bi bi-box-seam"></i>
            </div>
            <h2 class="fw-bold text-gradient2 mb-0">Minhas Coleções</h2>
        </div>

        <div>
            <a href="<?= Url::to(['colecao/create']) ?>" class="btn btn-gradient px-4 py-2 fw-semibold">
                <i class="bi bi-plus-lg me-2"></i> Criar Nova Coleção
            </a>
        </div>
    </header>

    <main class="container text-center">
        <h3 class="fw-bold mb-4 text-light">As Suas Coleções</h3>

        <div class="collections-grid d-flex flex-wrap justify-content-start gap-4 mb-5">

            <?php if (empty($collections)): ?>
                
                <div class="text-light text-opacity-75 mt-4">
                    <p class="fs-4 mb-1">Ainda não tem coleções.</p>
                    <p>Comece por <a href="<?= Url::to(['colecao/create']) ?>" class="link-light fw-semibold text-decoration-none">criar a sua primeira coleção</a>.</p>
                </div>
            
            <?php else: ?>

                <?php foreach ($collections as $collection): ?>
                    <div style="max-width: 320px; width: 100%;">
                        <div class="card bg-dark border-secondary h-100 rounded-4 shadow-sm hover-shadow overflow-hidden" style="border-color: rgba(142,45,226,0.25) !important;">
                            <div class="card-body p-4 position-relative">
                                <a href="<?= Url::to(['colecao/view', 'id' => $collection->id]) ?>" class="stretched-link"></a>
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div>
                                        <span class="badge bg-gradient text-uppercase mb-2 d-inline-block px-3 py-2 rounded-pill" style="background: linear-gradient(135deg, #8e2de2, #4a00e0); font-size: 0.7rem; letter-spacing: 0.05em;">Coleção</span>
                                        <h5 class="fw-bold text-light mb-0"><?= Html::encode($collection->nome) ?></h5>
                                    </div>
                                </div>
                                <p class="mb-0 text-secondary small">
                                    <?= count($collection->itens ?? []) ?> itens
                                </p>
                            </div>
                            <div class="card-footer border-0 bg-transparent px-4 pb-4 pt-0">
                                <div class="d-flex gap-2 flex-wrap">
                                    <?= Html::a('Ver coleção', ['colecao/view', 'id' => $collection->id], ['class' => 'btn btn-sm btn-gradient flex-grow-1 text-uppercase fw-semibold', 'style' => 'font-size: 0.75rem; padding: 0.4rem 0.8rem;']) ?>
                                    <?= Html::a('Editar', ['colecao/update', 'id' => $collection->id], ['class' => 'btn btn-sm btn-outline-light flex-grow-1 text-uppercase fw-semibold', 'style' => 'font-size: 0.75rem; padding: 0.4rem 0.8rem;']) ?>
                                    <?= Html::a('Apagar', ['colecao/delete', 'id' => $collection->id], [
                                        'class' => 'btn btn-sm btn-outline-danger flex-grow-1 text-uppercase fw-semibold',
                                        'style' => 'font-size: 0.75rem; padding: 0.4rem 0.8rem;',
                                        'data' => [
                                            'confirm' => 'Tem a certeza que quer apagar esta coleção?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </main>

</div>