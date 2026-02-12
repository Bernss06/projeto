<?php

use yii\helpers\Html;

/** @var \common\models\User $identity */
$identity = Yii::$app->user->identity;
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">
        <?php if ($identity): ?>
            <li class="nav-item mr-2">
                <span class="user-badge">
                    <img src="<?= $identity->getProfilePictureUrl() ?>" class="img-circle elevation-1 mr-1" alt="User Image" style="width: 25px; height: 25px; object-fit: cover;">
                    <?= Html::encode($identity->username) ?>
                </span>
            </li>
            <li class="nav-item">
                <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], [
                    'data-method' => 'post', 
                    'class' => 'nav-link', 
                    'title' => 'Terminar Sessão'
                ]) ?>
            </li>
        <?php else: ?>
            <li class="nav-item">
                <?= Html::a('Login', ['/site/login'], ['class' => 'nav-link']) ?>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Ecrã Completo">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->