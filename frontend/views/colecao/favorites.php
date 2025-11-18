<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Colecao[] $collections */
/** @var int[] $favoriteIds */

$this->title = 'Coleções Favoritas';
$favoriteSet = array_flip($favoriteIds);
?>

<div class="colecao-favorites container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h1 class="fw-bold text-gradient2 mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-secondary mb-0">As coleções públicas que guardou como favoritas.</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('Coleções Públicas', ['colecao/index'], ['class' => 'btn btn-dark-alt']) ?>
            <?= Html::a('Minhas Coleções', ['site/dashboard'], ['class' => 'btn btn-gradient']) ?>
        </div>
    </div>

    <?php if (empty($collections)): ?>
        <div class="text-center text-secondary py-5">
            Ainda não adicionou nenhuma coleção aos favoritos.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 collections-grid">
            <?php foreach ($collections as $collection): ?>
                <div class="col">
                    <div class="card bg-dark border-secondary h-100 rounded-4 shadow-sm overflow-hidden position-relative">
                        <?= Html::beginForm(['colecao/unfavorite', 'id' => $collection->id], 'post', ['class' => 'favorite-form', 'data-collection-id' => $collection->id]) ?>
                            <button type="submit" class="favorite-heart favorited" aria-label="Remover dos favoritos">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#ff0000" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        <?= Html::endForm() ?>
                        <div class="card-body p-4">
                            <span class="badge bg-gradient text-uppercase mb-3 d-inline-block px-3 py-2 rounded-pill" style="background: linear-gradient(135deg, #8e2de2, #4a00e0); font-size: 0.7rem; letter-spacing: 0.05em;">
                                Coleção favorita
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

