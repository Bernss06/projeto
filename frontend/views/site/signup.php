<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Criar Conta';
?>

<div class="login-page d-flex flex-column align-items-center justify-content-center py-5">

    <div class="login-logo text-center mb-4">
        <div class="icon-box mx-auto mb-3">
            <i class="bi bi-box-seam"></i>
        </div>
        <h2 class="text-gradient fw-bold">Minhas Coleções</h2>
    </div>

    <div class="login-card p-4 p-md-5 shadow-lg rounded-4">
        <h3 class="fw-bold mb-2 text-light">Crie sua conta</h3>
        <p class="text-muted mb-4">Comece a organizar suas coleções hoje mesmo</p>

        <div class="tab-switch d-flex mb-4">
            <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" class="tab-link">Entrar</a>
            <a href="#" class="tab-link active">Criar Conta</a>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'username', [
            'inputOptions' => ['placeholder' => 'Seu nome', 'class' => 'form-control custom-input']
        ])->label('Nome') ?>

        <?= $form->field($model, 'email', [
            'inputOptions' => ['placeholder' => 'seu@email.com', 'class' => 'form-control custom-input']
        ])->label('Email') ?>

        <?= $form->field($model, 'password', [
            'inputOptions' => ['placeholder' => '••••••••', 'class' => 'form-control custom-input']
        ])->passwordInput()->label('Senha') ?>

        <div class="d-grid mt-4">
            <?= Html::submitButton('Criar Conta', ['class' => 'btn btn-gradient py-2 fw-semibold', 'name' => 'signup-button']) ?>
        </div>

        <div class="divider my-4">
            <span>OU</span>
        </div>

        <div class="d-grid">
            <a href="#" class="btn btn-dark-alt">Continuar como Visitante</a>
        </div>

        <div class="mt-4 text-center small text-muted">
            Já tem uma conta?
            <?= Html::a('Entrar', ['site/login'], ['class' => 'text-gradient']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
