<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
?>

<div class="login-page d-flex flex-column align-items-center justify-content-center py-5">

    <div class="login-logo text-center mb-4">
        <div class="icon-box mx-auto mb-3">
            <i class="bi bi-box-seam"></i>
        </div>
        <h2 class="text-gradient fw-bold">Minhas Coleções</h2>
    </div>

    <div class="login-card p-4 p-md-5 shadow-lg rounded-4">
        <h3 class="fw-bold mb-2 text-light">Bem-vindo</h3>
        <p class="text-muted mb-4">Entre ou crie uma conta para gerenciar suas coleções</p>

        <div class="tab-switch d-flex mb-4">
            <a href="#" class="tab-link active">Entrar</a>
            <a href="#" class="tab-link">Criar Conta</a>
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

        <div class="divider my-4">
            <span>OU</span>
        </div>

        <div class="d-grid">
            <a href="#" class="btn btn-dark-alt">Entrar como Visitante</a>
        </div>

        <div class="mt-4 text-center small text-muted">
            Esqueceu a senha?
            <?= Html::a('Redefinir', ['site/request-password-reset'], ['class' => 'text-gradient']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
