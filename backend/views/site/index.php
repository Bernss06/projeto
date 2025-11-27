<?php
use yii\widgets\ListView;
use yii\helpers\Html;

$this->title = 'Gestão de Utilizadores';
?>

<div class="site-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">👥 Utilizadores Registados</h1>
        
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n<div class='d-flex justify-content-center mt-4'>{pager}</div>",
        'options' => ['class' => 'row'], // Container dos cartões (Row do Bootstrap)
        'itemOptions' => ['class' => 'col-md-4 col-lg-4 mb-4'], // Cada cartão ocupa 4 colunas (3 por linha)
        'emptyText' => '<div class="alert alert-info">Nenhum utilizador encontrado.</div>',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_user_card', ['model' => $model]);
        },
    ]) ?>
</div>