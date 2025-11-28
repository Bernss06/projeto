<?php
use yii\helpers\Html;

$this->title = 'Dashboard';
?>

<div class="site-index">
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 fade-in">
        <div>
            <h1 class="h3 font-weight-bold text-gray-800">👋 Olá, <?= Yii::$app->user->identity->username ?>!</h1>
            <p class="text-muted">Bem-vindo ao painel de administração.</p>
        </div>
        <div class="date-badge">
            <i class="far fa-calendar-alt mr-2"></i> <?= date('d M, Y') ?>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="row">
        <!-- Users Card -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-primary elevation-2 rounded-lg">
                <div class="inner">
                    <h3><?= $totalUsers ?></h3>
                    <p>Utilizadores Registados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="<?= \yii\helpers\Url::to(['/user/index']) ?>" class="small-box-footer">
                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Collections Card -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-success elevation-2 rounded-lg">
                <div class="inner">
                    <h3><?= $totalCollections ?></h3>
                    <p>Coleções Criadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <a href="<?= \yii\helpers\Url::to(['/colecao/index']) ?>" class="small-box-footer">
                    Gerir Coleções <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- Items Card -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-warning elevation-2 rounded-lg">
                <div class="inner">
                    <h3><?= $totalItems ?></h3>
                    <p>Itens Totais</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Mais info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
        <?php if (empty($userChartsData)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Ainda não existem dados suficientes para gerar gráficos por utilizador.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($userChartsData as $index => $chart): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-lg mb-4">
                        <div class="card-header border-0 bg-white py-3">
                            <h3 class="card-title font-weight-bold text-primary">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Coleções de <?= Html::encode($chart['username']) ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="userChart_<?= $index ?>" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- ChartJS Script -->
<?php
if (!empty($userChartsData)) {
    $js = "";
    foreach ($userChartsData as $index => $chart) {
        $labels = json_encode(array_column($chart['data'], 'nome'));
        $values = json_encode(array_column($chart['data'], 'item_count'));
        $js .= "createChart('userChart_{$index}', $labels, $values);\n";
    }
    $this->registerJs($js);
}
?>