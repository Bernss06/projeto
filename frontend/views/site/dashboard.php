<?php

use yii\helpers\Html;
use yii\helpers\Url;

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
            <span class="me-3 text-light">Olá, <strong><?= Html::encode(Yii::$app->user->identity->username ?? 'Utilizador') ?></strong></span>
            <?= Html::a('Sair', ['site/logout'], [
                'data-method' => 'post',
                'class' => 'btn btn-outline-light btn-sm'
            ]) ?>
        </div>
    </header>

    <main class="container text-center">
        <h3 class="fw-bold mb-4 text-light">As Suas Coleções</h3>

        <div class="collections-grid d-flex flex-wrap justify-content-center gap-4 mb-5">
            <!-- Aqui renderizarias as coleções do utilizador -->
            <div class="collection-card p-4 rounded-4">
                <h5 class="fw-semibold mb-1">Monstros</h5>
                <p class="text-muted mb-2">20 itens</p>
                <a href="#" class="btn btn-outline-light btn-sm">Ver Coleção</a>
            </div>

            <div class="collection-card p-4 rounded-4">
                <h5 class="fw-semibold mb-1">Moedas Antigas</h5>
                <p class="text-muted mb-2">12 itens</p>
                <a href="#" class="btn btn-outline-light btn-sm">Ver Coleção</a>
            </div>
        </div>

        <div class="dashboard-buttons d-flex flex-wrap justify-content-center gap-3">
            <a href="#" class="btn btn-gradient px-4 py-2 fw-semibold">
                <i class="bi bi-plus-lg me-2"></i> Criar Nova Coleção
            </a>

            <a href="<?= Url::to(['site/public-collections']) ?>" class="btn btn-dark-alt px-4 py-2 fw-semibold">
                <i class="bi bi-globe2 me-2"></i> Coleções Públicas
            </a>

            <a href="<?= Url::to(['site/favorites']) ?>" class="btn btn-dark-alt px-4 py-2 fw-semibold">
                <i class="bi bi-heart-fill me-2"></i> Favoritas
            </a>

            <a href="<?= Url::to(['site/settings']) ?>" class="btn btn-dark-alt px-4 py-2 fw-semibold">
                <i class="bi bi-gear-fill me-2"></i> Configurações
            </a>
        </div>
    </main>
</div>
