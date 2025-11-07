<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Colecao $model */
/** @var array $categoriasList */ // Este array deve ser passado pelo seu controller

$this->title = 'Criar Nova Coleção';


 $this->params['hideNavBar'] = true;
 $this->params['hideFooter'] = true;
?>

<div class="colecao-create-page d-flex justify-content-center align-items-start py-5">
    
    <div class="collection-form-wrapper p-4 p-md-5 rounded-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-light mb-0"><?= Html::encode($this->title) ?></h2>
            
            <?= Html::a(
                '<i class="bi bi-x-lg"></i>', 
                ['colecao/index'], // Mude para a rota do seu dashboard (ex: 'site/dashboard')
                ['class' => 'btn-close btn-close-white', 'aria-label' => 'Fechar']
            ) ?>
        </div>

        <?= $this->render('_form', [
            'model' => $model,
            'categoriasList' => $categoriasList,
        ]) ?>
        
    </div>

</div>