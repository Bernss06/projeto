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
            <h2 class="fw-bold text-gradient mb-0">Minhas Coleções</h2>
        </div>

        <div>
            <a href="<?= Url::to(['site/create']) ?>" class="btn btn-gradient px-4 py-2 fw-semibold">
                <i class="bi bi-plus-lg me-2"></i> Criar Nova Coleção
            </a>
        </div>
    </header>

    <main class="container text-center">
        <h3 class="fw-bold mb-4 text-light">As Suas Coleções</h3>

        <div class="collections-grid d-flex flex-wrap justify-content-center gap-4 mb-5">

            <?php if (empty($collections)): ?>
                
                <div class="text-light text-opacity-75 mt-4">
                    <p class="fs-4 mb-1">Ainda não tem coleções.</p>
                    <p>Comece por <a href="<?= Url::to(['colecao/create']) ?>" class="link-light fw-semibold text-decoration-none">criar a sua primeira coleção</a>.</p>
                </div>
            
            <?php else: ?>

                <?php foreach ($collections as $collection): ?>
                    
                    <div class="collection-card p-4 rounded-4">
                        <h5 class="fw-semibold mb-1">
                            <?= Html::encode($collection->nome) ?>
                        </h5>
                        <p class="text-muted mb-2">
                            <?= count($collection->items) ?> itens
                        </p>
                        
                        <a href="<?= Url::to(['colecao/view', 'id' => $collection->id]) ?>" class="btn btn-outline-light btn-sm">
                            Ver Coleção
                        </a>
                    </div>
                
                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </main>

</div>