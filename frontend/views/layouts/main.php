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
    
    // "Coleções Públicas" - Estilo "igual ao sair"
    $menuItems[] = [
        'label' => 'Coleções Públicas', // Ícone removido
        'url' => ['site/public-collections'],
        'linkOptions' => [
            'class' => 'btn btn-primary' // Classe alterada
        ],
        'options' => ['class' => 'nav-item ms-lg-2'] // Adiciona margem ao <li>
    ];
    
    // "Favoritas" - Estilo "igual ao sair"
    $menuItems[] = [
        'label' => 'Favoritas', // Ícone removido
        'url' => ['site/favorites'],
        'linkOptions' => [
            'class' => 'btn btn-outline-light' // Classe alterada
        ],
        'options' => ['class' => 'nav-item ms-lg-2'] // Adiciona margem ao <li>
    ];
    
    // "Configurações" - Estilo "igual ao sair"
    $menuItems[] = [
        'label' => 'Configurações', // Ícone removido
        'url' => ['site/settings'],
        'linkOptions' => [
            'class' => 'btn btn-outline-light' // Classe alterada
        ],
        'options' => ['class' => 'nav-item ms-lg-2'] // Adiciona margem ao <li>
    ];

    // Item "Sair" - Permanece igual
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex ms-lg-2'])
        . Html::submitButton(
            'Sair (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-outline-danger']
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