<?php

namespace frontend\controllers;

use common\models\Categoria;
use common\models\Colecao;
use common\models\Gosto;
use common\models\Item;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ItemController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'like', 'unlike'],
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
                    'like' => ['POST'],
                    'unlike' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(int $colecaoId): string
    {
        $colecao = $this->findColecao($colecaoId);
        $colecao->ensureCanView();

        $dataProvider = new ActiveDataProvider([
            'query' => $colecao->getItens(),
            'pagination' => ['pageSize' => 12],
        ]);

        return $this->render('index', [
            'colecao' => $colecao,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $model->ensureCanView();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate(int $colecaoId)
    {
        $colecao = $this->findColecao($colecaoId);
        if (!$colecao->canEdit()) {
            throw new NotFoundHttpException('Coleção não encontrada.');
        }

        $model = new Item();
        $model->colecao_id = $colecao->id;

        $categories = $this->getCategoryOptions();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->categoria_id === null || $model->categoria_id === '') {
                $model->categoria_id = 0;
            }
            $uploadedFile = \yii\web\UploadedFile::getInstanceByName('foto');
            if ($uploadedFile && $uploadedFile->error === UPLOAD_ERR_OK) {
                $uploadDir = Yii::getAlias('@frontend/web/uploads/items/');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = $uploadedFile->baseName . '_' . time() . '.' . $uploadedFile->extension;
                $filePath = $uploadDir . $fileName;
                if ($uploadedFile->saveAs($filePath)) {
                    $model->nome_foto = $fileName;
                }
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Item adicionado com sucesso.');
                return $this->redirect(['colecao/view', 'id' => $colecao->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'colecao' => $colecao,
            'categories' => $categories,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $model->ensureCanEdit();

        $categories = $this->getCategoryOptions();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->categoria_id === null || $model->categoria_id === '') {
                $model->categoria_id = 0;
            }
            $uploadedFile = \yii\web\UploadedFile::getInstanceByName('foto');
            if ($uploadedFile && $uploadedFile->error === UPLOAD_ERR_OK) {
                $uploadDir = Yii::getAlias('@frontend/web/uploads/items/');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                // Remove imagem antiga se existir
                if ($model->nome_foto) {
                    $oldFilePath = $uploadDir . $model->nome_foto;
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                $fileName = $uploadedFile->baseName . '_' . time() . '.' . $uploadedFile->extension;
                $filePath = $uploadDir . $fileName;
                if ($uploadedFile->saveAs($filePath)) {
                    $model->nome_foto = $fileName;
                }
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Item atualizado com sucesso.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'colecao' => $model->colecao,
            'categories' => $categories,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $model->ensureCanEdit();
        $colecaoId = $model->colecao_id;
        $model->delete();
        Yii::$app->session->setFlash('success', 'Item removido.');
        return $this->redirect(['index', 'colecaoId' => $colecaoId]);
    }

    public function actionLike(int $id): Response
    {
        $item = $this->findModel($id);
        $item->ensureCanView();

        // Check if already liked by this user
        $gosto = Gosto::findOne([
            'item_id' => $item->id,
            'user_id' => Yii::$app->user->id
        ]);

        if (!$gosto) {
            $gosto = new Gosto([
                'item_id' => $item->id,
                'user_id' => Yii::$app->user->id
            ]);
            if (!$gosto->save()) {
                Yii::$app->session->setFlash('error', 'Erro ao gostar: ' . print_r($gosto->errors, true));
            }
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['colecao/view', 'id' => $item->colecao_id]);
    }

    public function actionUnlike(int $id): Response
    {
        $item = $this->findModel($id);
        $item->ensureCanView();

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        // Use deleteAll to ensure we only delete based on the composite key (item + user)
        // This avoids issues if the primary key 'id' is not unique (e.g. all 0)
        Gosto::deleteAll([
            'item_id' => $item->id,
            'user_id' => Yii::$app->user->id
        ]);

        return $this->redirect(Yii::$app->request->referrer ?: ['colecao/view', 'id' => $item->colecao_id]);
    }

    protected function findModel(int $id): Item
    {
        $model = Item::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Item não encontrado.');
        }
        return $model;
    }

    protected function findColecao(int $id): Colecao
    {
        $colecao = Colecao::findOne($id);
        if ($colecao === null) {
            throw new NotFoundHttpException('Coleção não encontrada.');
        }
        return $colecao;
    }

    private function getCategoryOptions(): array
    {
        $options = Categoria::find()
            ->orderBy(['nome' => SORT_ASC])
            ->select(['nome', 'id'])
            ->asArray()
            ->all();

        $list = ['0' => 'Sem categoria'];
        foreach ($options as $option) {
            $list[(string)$option['id']] = $option['nome'];
        }
        return $list;
    }
}


