<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Colecao;
use common\models\Categoria;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Item $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dtaquisicao')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'nome_foto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
        ArrayHelper::map(Categoria::find()->all(), 'id', 'nome'),
        ['prompt' => 'Selecione uma categoria']
    ) ?>
    
    <!-- Colecao Field might be readonly or selectable depending on logic, keeping it selectable for admin management power -->
    <?= $form->field($model, 'colecao_id')->dropDownList(
        ArrayHelper::map(Colecao::find()->all(), 'id', 'nome'),
        ['prompt' => 'Selecione uma coleção']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
