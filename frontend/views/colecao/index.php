<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Colecao[] $collections */
/** @var int[] $favoriteIds */

$this->title = 'Coleções Públicas';
$userIsGuest = Yii::$app->user->isGuest;
$favoriteSet = array_flip($favoriteIds);
?>

<div class="colecao-public container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h1 class="fw-bold text-gradient2 mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-secondary mb-0">Coleções partilhadas pela comunidade.</p>
        </div>
        <div class="d-flex gap-2">
            <?php if ($userIsGuest): ?>
                <?= Html::a('Entrar', ['site/login'], ['class' => 'btn btn-dark-alt']) ?>
                <?= Html::a('Criar Conta', ['site/signup'], ['class' => 'btn btn-gradient']) ?>
            <?php else: ?>
                <?= Html::a('Minhas Coleções', ['site/dashboard'], ['class' => 'btn btn-gradient']) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($collections)): ?>
        <div class="text-center text-secondary py-5">
            Ainda não existem coleções públicas.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 collections-grid">
            <?php foreach ($collections as $collection): ?>
                <div class="col">
                    <div class="card bg-dark border-secondary h-100 rounded-4 shadow-sm overflow-hidden position-relative">
                        <?php if (!$userIsGuest): ?>
                            <?php 
                            $isFavorited = isset($favoriteSet[$collection->id]);
                            $formAction = $isFavorited ? ['colecao/unfavorite', 'id' => $collection->id] : ['colecao/favorite', 'id' => $collection->id];
                            ?>
                            <?= Html::beginForm($formAction, 'post', ['class' => 'favorite-form', 'data-collection-id' => $collection->id]) ?>
                                <button type="submit" class="btn btn-link p-0 text-decoration-none" aria-label="<?= $isFavorited ? 'Remover dos favoritos' : 'Adicionar aos favoritos' ?>">
                                    <?php if ($isFavorited): ?>
                                        <!-- Solid Yellow Star -->
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#ffc107" stroke="#ffc107" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    <?php else: ?>
                                        <!-- Outline White Star -->
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    <?php endif; ?>
                                </button>
                            <?= Html::endForm() ?>
                        <?php endif; ?>
                        <div class="card-body p-4">
                            <span class="badge bg-gradient text-uppercase mb-3 d-inline-block px-3 py-2 rounded-pill" style="background: linear-gradient(135deg, #8e2de2, #4a00e0); font-size: 0.7rem; letter-spacing: 0.05em;">
                                Coleção
                            </span>
                            <h4 class="text-light fw-semibold mb-2"><?= Html::encode($collection->nome) ?></h4>
                            <p class="text-secondary small mb-3">
                                <?= Html::encode($collection->descricao ?: 'Sem descrição disponível.') ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center text-secondary small">
                                <span><?= $collection->getFavoritosCount() ?> favoritos</span>
                                <span><?= Html::encode(Yii::$app->formatter->asDate($collection->updated_at, 'php:d/m/Y')) ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                            <?= Html::a('Ver coleção', ['colecao/view', 'id' => $collection->id], ['class' => 'btn btn-sm btn-gradient w-100 text-uppercase fw-semibold', 'style' => 'font-size: 0.75rem; padding: 0.4rem 0.8rem;']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


