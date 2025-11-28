<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::to(['/site/index']) ?>" class="brand-link text-center">
        <i class="fas fa-layer-group brand-icon"></i>
        <span class="brand-text font-weight-bold ml-2">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-4 pb-3 mb-4 d-flex align-items-center">
            <div class="image">
                <div class="user-avatar">
                    <?= strtoupper(substr(Yii::$app->user->identity->username, 0, 1)) ?>
                </div>
            </div>
            <div class="info">
                <a href="#" class="d-block font-weight-semibold"><?= Yii::$app->user->identity->username ?></a>
                <small class="text-muted">Administrator</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'ADMINISTRAÇÃO', 'header' => true],
                    ['label' => 'Gestão de Utilizadores', 'icon' => 'users', 'url' => ['/user/index'], 'visible' => !Yii::$app->user->isGuest],
                    ['label' => 'Gestão de Coleções', 'icon' => 'layer-group', 'url' => ['/colecao/index'], 'visible' => !Yii::$app->user->isGuest],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>