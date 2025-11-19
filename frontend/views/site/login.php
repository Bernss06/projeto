<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->params['hideNavBar'] = true;
$this->params['hideFooter'] = true;
?>

<div class="login-page d-flex flex-column align-items-center justify-content-center py-5">

    <div class="login-logo text-center mb-5">
        <div class="login-logo-icon mx-auto mb-3">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
        <h2 class="login-logo-text fw-bold">
            <span class="logo-minhas">Teasure</span>
            <span class="logo-colecoes">Hub</span>
        </h2>
    </div>

    <div class="login-card p-4 p-md-5 shadow-lg rounded-4">
        <h3 class="fw-bold mb-2 text-light">Bem-vindo</h3>
        <p class="text-secondary mb-4">Entre ou crie uma conta para gerenciar suas coleções</p>

        <div class="tab-switch d-flex mb-4">
            <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" class="tab-link active">Entrar</a>
            <a href="<?= \yii\helpers\Url::to(['site/signup']) ?>" class="tab-link">Criar Conta</a>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username', [
            'inputOptions' => ['placeholder' => 'seu@email.com', 'class' => 'form-control custom-input']
        ])->label('Email') ?>

        <?= $form->field($model, 'password', [
            'inputOptions' => ['placeholder' => '••••••••', 'class' => 'form-control custom-input']
        ])->passwordInput()->label('Senha') ?>

        <div class="d-grid mt-4">
            <?= Html::submitButton('Entrar', ['class' => 'btn btn-gradient py-2 fw-semibold', 'name' => 'login-button']) ?>
        </div>


        <div class="mt-4 text-center small text-muted">
            Esqueceu a senha?
            <?= Html::a('Redefinir', ['site/request-password-reset'], ['class' => 'text-gradient2']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
