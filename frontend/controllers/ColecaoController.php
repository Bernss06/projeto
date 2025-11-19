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

        // A tabela favorito não tem user_id, então mostramos todos os favoritos
        $favoriteIds = ColecaoFavorito::find()
            ->select('coelcao_id')
            ->column();

        return $this->render('index', [
            'collections' => $collections,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function actionMine(): string
    {
        $query = Colecao::find()->andWhere(['user_id' => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12],
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]],
        ]);

        return $this->render('mine', [
            'dataProvider' => $dataProvider,
        ]);
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
        return $this->redirect(['mine']);
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

        // A tabela favorito não tem user_id, então verificamos apenas se já existe
        $favorito = ColecaoFavorito::findOne(['coelcao_id' => $colecao->id]);
        if (!$favorito) {
            $favorito = new ColecaoFavorito([
                'coelcao_id' => $colecao->id,
            ]);
            $favorito->save();
            Yii::$app->session->setFlash('success', 'Coleção adicionada aos favoritos.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionUnfavorite(int $id): Response
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('Página não encontrada.');
        }

        $favorito = ColecaoFavorito::findOne(['coelcao_id' => $id]);
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

        // A tabela favorito não tem user_id, então mostramos todas as coleções favoritas
        $favoritoIds = ColecaoFavorito::find()->select('coelcao_id')->column();
        $collections = Colecao::find()
            ->where(['status' => 1, 'id' => $favoritoIds]) // Apenas públicas que estão nos favoritos
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


