<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var frontend\models\UserSettingsForm $model */
/** @var common\models\User $user */

$this->title = 'Configurações';
?>

<div class="settings-page container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="text-center mb-4">
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
                <h1 class="fw-bold text-gradient2 mb-1"><?= Html::encode($this->title) ?></h1>
                <p class="text-secondary mb-0">Atualize os seus dados pessoais e preferências.</p>
            </div>

            <div class="form-card">
                <?php $form = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data'],
                    'fieldConfig' => [
                        'options' => ['class' => 'mb-4'],
                        'labelOptions' => ['class' => 'form-label text-uppercase small fw-semibold text-secondary'],
                        'inputOptions' => ['class' => 'form-control custom-input'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block mt-2'],
                    ],
                ]); ?>

                <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

                <div class="text-center mb-5">
                    <div class="position-relative d-inline-block">
                        <?php 
                            $pfpName = $user->pfpimage ? $user->pfpimage->nome : 'pfppadrao.png';
                            $pfpUrl = Yii::getAlias('@web/uploads/pfp/' . $pfpName);
                        ?>
                        <img src="<?= $pfpUrl ?>" alt="Profile Picture" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid white;">
                        <label for="usersettingsform-profileimage" class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 cursor-pointer" style="cursor: pointer;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </label>
                    </div>
                </div>

                <div class="d-none">
                    <?= $form->field($model, 'profileImage', [
                        'template' => "{input}",
                        'options' => ['class' => 'mb-0']
                    ])->fileInput(['accept' => 'image/*', 'id' => 'usersettingsform-profileimage']) ?>
                </div>
                <div class="text-center text-danger mb-3">
                    <?= Html::error($model, 'profileImage') ?>
                </div>

                <?= $form->field($model, 'username')->textInput(['placeholder' => 'Seu nome']) ?>

                <?= $form->field($model, 'email')->input('email', ['placeholder' => 'seu@email.com']) ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Nova password']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => 'Confirme a password']) ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <?= Html::a('Cancelar', ['site/dashboard'], ['class' => 'btn btn-dark-alt px-4']) ?>
                    <?= Html::submitButton('Guardar Alterações', ['class' => 'btn btn-gradient px-4']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


