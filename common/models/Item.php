<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;

/**
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property string|null $nota
 * @property string|null $dtaquisicao
 * @property string|null $nome_foto
 * @property int|null $categoria_id
 * @property int $colecao_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Colecao $colecao
 */
class Item extends ActiveRecord
{
    public static function tableName()
    {
        return 'item';
    }

    public function rules()
    {
        return [
            [['nome', 'colecao_id'], 'required'],
            [['descricao', 'nota', 'nome_foto'], 'string', 'max' => 250],
            [['nome'], 'string', 'max' => 250],
            [['dtaquisicao'], 'date', 'format' => 'php:Y-m-d'],
            [['categoria_id', 'colecao_id'], 'integer'],
            [['categoria_id'], 'default', 'value' => 0],
            [['descricao', 'nota', 'dtaquisicao', 'nome_foto'], 'default', 'value' => null],
            [['colecao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Colecao::class, 'targetAttribute' => ['colecao_id' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        // Garantir que categoria_id não seja NULL (valor padrão 0)
        if ($this->categoria_id === null || $this->categoria_id === '') {
            $this->categoria_id = 0;
        }
        return true;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'nota' => 'Nota',
            'dtaquisicao' => 'Data de Aquisição',
            'nome_foto' => 'Nome da Foto',
            'categoria_id' => 'Categoria',
            'colecao_id' => 'Coleção',
        ];
    }

    public function getColecao()
    {
        return $this->hasOne(Colecao::class, ['id' => 'colecao_id']);
    }

    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    public function getCategoriaNome(): string
    {
        if ($this->categoria_id === null || (int)$this->categoria_id === 0) {
            return 'Sem categoria';
        }
        return $this->categoria?->nome ?? 'Sem categoria';
    }

    public function getImagemUrl(): string
    {
        if (!$this->nome_foto) {
            return 'https://via.placeholder.com/400x260?text=Sem+Imagem';
        }
        return Yii::getAlias('@web/uploads/items/' . $this->nome_foto);
    }

    public function ensureCanView(): void
    {
        $colecao = $this->colecao;
        if ($colecao === null) {
            throw new ForbiddenHttpException('Coleção não encontrada.');
        }
        $colecao->ensureCanView();
    }

    public function ensureCanEdit(): void
    {
        $colecao = $this->colecao;
        if ($colecao === null || !$colecao->canEdit()) {
            throw new ForbiddenHttpException('Você não tem permissão para alterar este item.');
        }
    }
}


