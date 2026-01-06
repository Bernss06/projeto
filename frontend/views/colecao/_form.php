<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Colecao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin([
    'options' => ['class' => 'colecao-form needs-validation'],
    'fieldConfig' => [
        'options' => ['class' => 'mb-4'],
        'labelOptions' => ['class' => 'form-label text-uppercase small fw-semibold text-secondary'],
        'errorOptions' => ['class' => 'invalid-feedback d-block mt-2'],
        'inputOptions' => ['class' => 'form-control custom-input'],
    ],
]); ?>

<?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'placeholder' => 'Ex.: Coleção de moedas raras']) ?>

<?= $form->field($model, 'descricao')->textarea([
    'rows' => 4,
    'placeholder' => 'Descreva rapidamente o tema ou história desta coleção',
    'class' => 'form-control custom-input',
]) ?>

<div class="mb-4">
    <label class="form-label text-uppercase small fw-semibold text-secondary d-block mb-2">Visibilidade</label>
    <div class="form-check form-switch ps-0 d-flex align-items-center justify-content-between bg-dark bg-opacity-25 rounded-3 px-3 py-2 border border-secondary border-opacity-25">
        <div>
            <span class="text-light fw-semibold d-block">Coleção Pública</span>
            <small class="text-secondary">Se ativado, todos poderão ver esta coleção.</small>
        </div>
        <?= $form->field($model, 'is_public', [
            'options' => ['class' => 'm-0'],
            'template' => '{input}{error}',
        ])->checkbox([
            'class' => 'form-check-input ms-3',
            'role' => 'switch',
        ], false) ?>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mt-4">
    <?= Html::submitButton($model->isNewRecord ? 'Criar' : 'Guardar', ['class' => 'btn btn-gradient px-4']) ?>
    <?= Html::a('Cancelar', ['site/dashboard'], ['class' => 'btn btn-dark-alt px-4']) ?>
</div>

<?php ActiveForm::end(); ?>

