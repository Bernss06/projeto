<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Item $model */
/** @var common\models\Colecao $colecao */
?>

/** @var array $categories */

<?php $form = ActiveForm::begin([
    'options' => ['class' => 'item-form needs-validation', 'enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'options' => ['class' => 'mb-4'],
        'labelOptions' => ['class' => 'form-label text-uppercase small fw-semibold text-secondary'],
        'errorOptions' => ['class' => 'invalid-feedback d-block mt-2'],
        'inputOptions' => ['class' => 'form-control custom-input'],
    ],
]); ?>

<?= Html::activeHiddenInput($model, 'colecao_id', ['value' => $colecao->id]) ?>

<?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'placeholder' => 'Nome do item']) ?>

<?= $form->field($model, 'descricao')->textarea([
    'rows' => 3,
    'placeholder' => 'Descrição breve do item',
    'class' => 'form-control custom-input',
]) ?>

<?= $form->field($model, 'nota')->textarea([
    'rows' => 2,
    'placeholder' => 'Notas pessoais ou observações',
    'class' => 'form-control custom-input',
]) ?>

<?= $form->field($model, 'dtaquisicao')->input('date', [
    'class' => 'form-control custom-input',
]) ?>

<div class="mb-4">
    <label class="form-label text-uppercase small fw-semibold text-secondary">Imagem</label>
    <div class="d-flex flex-column gap-2">
        <input type="file" name="foto" id="foto" accept="image/*" class="form-control custom-input">
        <small class="text-secondary">Selecione uma imagem para fazer upload</small>
        <?php if (!$model->isNewRecord && $model->nome_foto): ?>
            <div class="mt-2">
                <small class="text-secondary">Imagem atual: <?= Html::encode($model->nome_foto) ?></small>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $form->field($model, 'categoria_id')->dropDownList($categories, [
    'class' => 'form-select custom-input',
]) ?>

<div class="d-flex flex-wrap gap-2 mt-4">
    <?= Html::submitButton($model->isNewRecord ? 'Adicionar Item' : 'Guardar Alterações', ['class' => 'btn btn-gradient px-4']) ?>
    <?= Html::a('Cancelar', ['colecao/view', 'id' => $colecao->id], ['class' => 'btn btn-dark-alt px-4']) ?>
</div>

<?php ActiveForm::end(); ?>

