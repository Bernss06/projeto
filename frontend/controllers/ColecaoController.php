<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\models\Colecao;
use common\models\ColecaoFavorito;

class ColecaoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'mine', 'favorites', 'favorite', 'unfavorite'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'favorite' => ['POST'],
                    'unfavorite' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $collections = Colecao::find()
            ->where(['status' => 1])
            ->with(['favoritos'])
            ->orderBy(['updated_at' => SORT_DESC])
            ->all();

        // Obter IDs de favoritos do utilizador atual
        $favoriteIds = [];
        if (!Yii::$app->user->isGuest) {
            $favoriteIds = ColecaoFavorito::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->select('colecao_id')
                ->column();
        }

        return $this->render('index', [
            'collections' => $collections,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function actionMine(): Response
    {
        // As coleções são exibidas no dashboard
        return $this->redirect(['site/dashboard']);
    }

    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $model->ensureCanView();
        $items = $model->getItens()->with('categoria')->all();

        $isFavorited = false;
        if (!Yii::$app->user->isGuest) {
            $isFavorited = $model->isFavoritedByUser(Yii::$app->user->id);
        }

        return $this->render('view', [
            'model' => $model,
            'items' => $items,
            'isFavorited' => $isFavorited,
        ]);
    }

    public function actionCreate()
    {
        
        $model = new Colecao();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        if (!$model->canEdit()) {
            throw new NotFoundHttpException('Página não encontrada.');
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        if (!$model->canEdit()) {
            throw new NotFoundHttpException('Página não encontrada.');
        }
        $model->delete();
        return $this->redirect(['site/dashboard']);
    }

    public function actionFavorite(int $id): Response
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('Página não encontrada.');
        }

        $colecao = $this->findModel($id);
        if (!$colecao->isPublic() && !$colecao->canEdit()) {
            throw new NotFoundHttpException('Página não encontrada.');
        }

        // Verificar se o utilizador já favoritou esta coleção
        $favorito = ColecaoFavorito::findOne([
            'colecao_id' => $colecao->id,
            'user_id' => Yii::$app->user->id
        ]);
        
        if (!$favorito) {
            $favorito = new ColecaoFavorito([
                'colecao_id' => $colecao->id,
            ]);
            // user_id será definido automaticamente no beforeSave
            if ($favorito->save()) {
                Yii::$app->session->setFlash('success', 'Coleção adicionada aos favoritos.');
            }
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionUnfavorite(int $id): Response
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('Página não encontrada.');
        }

        // Remover apenas o favorito do utilizador atual
        $favorito = ColecaoFavorito::findOne([
            'colecao_id' => $id,
            'user_id' => Yii::$app->user->id
        ]);
        
        if ($favorito) {
            $favorito->delete();
            Yii::$app->session->setFlash('success', 'Coleção removida dos favoritos.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionFavorites(): string
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('Página não encontrada.');
        }

        // Obter IDs de coleções favoritadas pelo utilizador atual
        $favoritoIds = ColecaoFavorito::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->select('colecao_id')
            ->column();
            
        $collections = Colecao::find()
            ->where(['status' => 1, 'id' => $favoritoIds]) // Apenas públicas que estão nos favoritos do user
            ->with(['favoritos'])
            ->all();

        $favoriteIds = array_map(static fn($collection) => $collection->id, $collections);

        return $this->render('favorites', [
            'collections' => $collections,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    protected function findModel(int $id): Colecao
    {
        $model = Colecao::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Coleção não encontrada.');
        }
        return $model;
    }
}


