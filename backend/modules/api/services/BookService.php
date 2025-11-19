<?php
namespace backend\modules\api\services;

use common\models\Book;
use Yii;

class BookService
{
    public function create(array $data): Book
    {
        $model = new Book();
        $model->load($data, '');
        $model->created_by = Yii::$app->user->id;
        $model->created_at = time();
        $model->updated_at = time();

        if (!$model->save()) {
            throw new \DomainException('Book not created: ' . json_encode($model->errors));
        }

        return $model;
    }

    public function update(int $id, array $data): Book
    {
        $model = $this->findModel($id);

        $model->load($data, '');
        $model->updated_at = time();

        if (!$model->save()) {
            throw new \DomainException('Book not updated: ' . json_encode($model->errors));
        }

        return $model;
    }

    public function delete(int $id): void
    {
        $model = $this->findModel($id);

        if (!$model->delete()) {
            throw new \RuntimeException('Cannot delete book');
        }
    }

    private function findModel(int $id): Book
    {
        if (!$model = Book::findOne($id)) {
            throw new \DomainException('Book not found');
        }
        return $model;
    }
}
