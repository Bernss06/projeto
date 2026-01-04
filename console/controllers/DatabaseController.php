<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use common\models\User;
use common\models\Colecao;
use common\models\Item;
use common\models\ColecaoFavorito;
use common\models\Pfpimage;

/**
 * Comando para resetar a base de dados
 * 
 * Uso: php yii database/reset
 */
class DatabaseController extends Controller
{
    /**
     * Reset da base de dados - apaga todos os dados exceto admin e pfp padrão
     * 
     * @return int Exit code
     */
    public function actionReset()
    {
        $this->stdout("\n╔═══════════════════════════════════════════════════════════╗\n", \yii\helpers\Console::BG_RED);
        $this->stdout("║  AVISO: Isto irá APAGAR TODOS OS DADOS da base de dados! ║\n", \yii\helpers\Console::BG_RED);
        $this->stdout("║  Apenas o ADMIN e as PFP padrão (pfppadrao.png,          ║\n", \yii\helpers\Console::BG_RED);
        $this->stdout("║  admin.png) serão mantidos.                               ║\n", \yii\helpers\Console::BG_RED);
        $this->stdout("╚═══════════════════════════════════════════════════════════╝\n\n", \yii\helpers\Console::BG_RED);

        // Confirmar ação
        if (!$this->confirm('Tem certeza que deseja continuar?')) {
            $this->stdout("Operação cancelada.\n", \yii\helpers\Console::FG_YELLOW);
            return ExitCode::OK;
        }

        // Segunda confirmação
        if (!$this->confirm('Esta ação é IRREVERSÍVEL. Confirma novamente?')) {
            $this->stdout("Operação cancelada.\n", \yii\helpers\Console::FG_YELLOW);
            return ExitCode::OK;
        }

        $this->stdout("\nIniciando limpeza da base de dados...\n\n", \yii\helpers\Console::FG_YELLOW);

        try {
            // 1. Deletar favoritos
            $this->stdout("1. Apagando favoritos... ", \yii\helpers\Console::FG_CYAN);
            $favoritosCount = ColecaoFavorito::deleteAll();
            $this->stdout("✓ {$favoritosCount} favoritos apagados\n", \yii\helpers\Console::FG_GREEN);

            // 2. Deletar itens
            $this->stdout("2. Apagando itens... ", \yii\helpers\Console::FG_CYAN);
            $itensCount = Item::deleteAll();
            $this->stdout("✓ {$itensCount} itens apagados\n", \yii\helpers\Console::FG_GREEN);

            // 3. Deletar coleções
            $this->stdout("3. Apagando coleções... ", \yii\helpers\Console::FG_CYAN);
            $colecoesCount = Colecao::deleteAll();
            $this->stdout("✓ {$colecoesCount} coleções apagadas\n", \yii\helpers\Console::FG_GREEN);

            // 4. Limpar pfp PRIMEIRO (manter apenas as 2 padrão: pfppadrao.png e admin.png)
            $this->stdout("4. Limpando imagens de perfil (mantendo padrões)... ", \yii\helpers\Console::FG_CYAN);
            $pfpCount = Pfpimage::deleteAll(['NOT IN', 'id', [6, 8]]);
            $this->stdout("✓ {$pfpCount} imagens removidas (pfppadrao.png e admin.png mantidos)\n", \yii\helpers\Console::FG_GREEN);

            // 5. Deletar utilizadores (exceto admin) - DEPOIS de deletar pfp
            $this->stdout("5. Apagando utilizadores (exceto admin)... ", \yii\helpers\Console::FG_CYAN);
            
            // Encontrar o admin
            $admin = User::find()->where(['username' => 'admin'])->one();
            if (!$admin) {
                // Tentar encontrar por email
                $admin = User::find()->where(['email' => 'admin@admin.com'])->one();
            }
            if (!$admin) {
                // Tentar encontrar o primeiro user com id=1
                $admin = User::findOne(1);
            }

            if ($admin) {
                $usersCount = User::deleteAll(['<>', 'id', $admin->id]);
                $this->stdout("✓ {$usersCount} utilizadores apagados (admin preservado: {$admin->username})\n", \yii\helpers\Console::FG_GREEN);
            } else {
                $this->stdout("⚠ Admin não encontrado! Pulando...\n", \yii\helpers\Console::FG_YELLOW);
            }

            $this->stdout("\n╔═══════════════════════════════════════════════════════════╗\n", \yii\helpers\Console::BG_GREEN);
            $this->stdout("║           Base de dados limpa com sucesso!                ║\n", \yii\helpers\Console::BG_GREEN);
            $this->stdout("╚═══════════════════════════════════════════════════════════╝\n\n", \yii\helpers\Console::BG_GREEN);

            return ExitCode::OK;

        } catch (\Exception $e) {
            $this->stdout("\n✗ ERRO: " . $e->getMessage() . "\n", \yii\helpers\Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    /**
     * Mostra estatísticas da base de dados
     * 
     * @return int Exit code
     */
    public function actionStats()
    {
        $this->stdout("\n═══════ Estatísticas da Base de Dados ═══════\n\n", \yii\helpers\Console::FG_CYAN);

        $users = User::find()->count();
        $colecoes = Colecao::find()->count();
        $itens = Item::find()->count();
        $favoritos = ColecaoFavorito::find()->count();
        $pfps = Pfpimage::find()->count();

        $this->stdout("👥 Utilizadores: ", \yii\helpers\Console::FG_YELLOW);
        $this->stdout("{$users}\n", \yii\helpers\Console::BOLD);

        $this->stdout("📦 Coleções: ", \yii\helpers\Console::FG_YELLOW);
        $this->stdout("{$colecoes}\n", \yii\helpers\Console::BOLD);

        $this->stdout("📝 Itens: ", \yii\helpers\Console::FG_YELLOW);
        $this->stdout("{$itens}\n", \yii\helpers\Console::BOLD);

        $this->stdout("⭐ Favoritos: ", \yii\helpers\Console::FG_YELLOW);
        $this->stdout("{$favoritos}\n", \yii\helpers\Console::BOLD);

        $this->stdout("🖼️  Imagens de Perfil: ", \yii\helpers\Console::FG_YELLOW);
        $this->stdout("{$pfps}\n\n", \yii\helpers\Console::BOLD);

        return ExitCode::OK;
    }
}
