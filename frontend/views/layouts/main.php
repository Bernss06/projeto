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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?>
</head>

<body class="bg-dark text-light">
<?php
NavBar::begin([
    'brandLabel' => 'MyCollections',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary',
    ],
]);

$menuItems = [
    ['label' => 'Planos', 'url' => ['/site/planos']],
];

if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Entrar', 'url' => ['/site/login']];
    $menuItems[] = [
        'label' => 'Começar',
        'url' => ['/site/signup'],
        'linkOptions' => ['class' => 'btn btn-primary px-3 ms-lg-2'],
        'options' => ['class' => 'nav-item'],
    ];
} else {
    $menuItems[] = [
        'label' => 'Minhas Coleções',
        'url' => ['/colecao/index'],
    ];
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex ms-lg-2'])
        . Html::submitButton(
            'Sair (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-outline-light']
        )
        . Html::endForm()
        . '</li>';
}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto align-items-center'],
    'items' => $menuItems,
]);

NavBar::end();
?>
<?php $this->beginBody() ?>

<main>
  <?= $content ?>
</main>

<footer class="text-center py-4 mt-5 border-top border-secondary">
  <p class="mb-0 text-secondary">&copy; <?= date('Y') ?> MyCollections. Organize suas paixões.</p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
