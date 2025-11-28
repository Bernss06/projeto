<?php

use yii\helpers\Html;

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
        <li class="nav-item mr-2">
            <span class="user-badge">
                <i class="far fa-user-circle mr-1"></i>
                <?= Yii::$app->user->identity->username ?>
            </span>
        </li>
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], [
                'data-method' => 'post', 
                'class' => 'nav-link', 
                'title' => 'Terminar Sessão'
            ]) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Ecrã Completo">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->