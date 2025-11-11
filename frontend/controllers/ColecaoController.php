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

class ColecaoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'mine'],
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
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $query = Colecao::find()->andWhere(['status' => 1]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12],
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        return $this->render('view', [
            'model' => $model,
            'items' => $items,
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

    protected function findModel(int $id): Colecao
    {
        $model = Colecao::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Coleção não encontrada.');
        }
        return $model;
    }
}


