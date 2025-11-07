<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;



AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale-1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<?php if (!($this->params['hideNavBar'] ?? false)) : ?>
<?php
NavBar::begin([
    'brandLabel' => '<div class="navbar-brand-content">
        <div class="logo-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
        <span class="logo-text"><span class="logo-my">My</span><span class="logo-collections">Collections</span></span>
    </div>',
    'brandUrl' => Yii::$app->homeUrl,
    'brandOptions' => ['class' => 'navbar-brand-custom'],
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-custom navbar-dark',
    ],
]);

$menuItems = [];

if (Yii::$app->user->isGuest) {
    $menuItems[] = [
        'label' => 'Entrar', 
        'url' => ['/site/login'], 
        'linkOptions' => ['class' => 
        'btn btn-entrar']];
        
    $menuItems[] = [
        'label' => 'Começar <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'url' => ['/site/signup'],
        'linkOptions' => ['class' => 'btn btn-comecar'],
        'options' => ['class' => 'nav-item'],
        'encode' => false,
    ];
} else {
    // --- INÍCIO DAS ALTERAÇÕES ---

    // "Criar Nova Coleção" - Estilo de botão gradient
    $menuItems[] = [
        'label' => '<i class="bi bi-plus-lg me-2"></i> Criar Nova Coleção',
        'url' => ['site/create'], // NOTA: Mude para a rota correta, ex: ['/colecao/create']
        'encode' => false,
        'linkOptions' => [
            'class' => 'btn btn-gradient px-4 py-2 fw-semibold'
        ],
        'options' => ['class' => 'nav-item ms-lg-2'] // Adiciona margem ao <li>
    ];
    
    // "Coleções Públicas" - Estilo de botão dark-alt
    $menuItems[] = [
        'label' => '<i class="bi bi-globe2 me-2"></i> Coleções Públicas',
        'url' => ['site/public'],
        'encode' => false,
        'linkOptions' => [
            'class' => 'btn btn-dark-alt px-4 py-2 fw-semibold'
        ],
        'options' => ['class' => 'nav-item ms-lg-2'] // Adiciona margem ao <li>
    ];
    
    // "Favoritas" - Estilo de botão dark-alt
    $menuItems[] = [
        'label' => '<i class="bi bi-heart-fill me-2"></i> Favoritas',
        'url' => ['site/favorites'],
        'encode' => false,
        'linkOptions' => [
            'class' => 'btn btn-dark-alt px-4 py-2 fw-semibold'
        ],
        'options' => ['class' => 'nav-item ms-lg-2'] // Adiciona margem ao <li>
    ];
    
    // "Configurações" - Estilo de botão dark-alt
    $menuItems[] = [
        'label' => '<i class="bi bi-gear-fill me-2"></i> Configurações',
        'url' => ['site/settings'],
        'encode' => false,
        'linkOptions' => [
            'class' => 'btn btn-dark-alt px-4 py-2 fw-semibold'
        ],
        'options' => ['class' => 'nav-item ms-lg-2'] // Adiciona margem ao <li>
    ];

    // Item "Sair" que já existia (mantém o estilo 'btn-outline-light')
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex ms-lg-2'])
        . Html::submitButton(
            'Sair (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-outline-light']
        )
        . Html::endForm()
        . '</li>';
    
    // --- FIM DAS ALTERAÇÕES ---
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto align-items-center'],
    'items' => $menuItems,
]);

NavBar::end();
?>
<?php endif; ?>

<main>
  <?= $content ?>
</main>

<?php if (!($this->params['hideFooter'] ?? false)) : ?>
<footer class="text-center py-4 mt-5 border-top border-secondary">
  <p class="mb-0 text-secondary">&copy; <?= date('Y') ?> MyCollections. Organize suas paixões.</p>
</footer>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>