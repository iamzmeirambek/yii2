<?php
namespace common\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%book}}';
    }

    public function rules()
    {
        return [
            [['title', 'author'], 'required'],
            [['description'], 'string'],
            [['published_at'], 'safe'],
            [['created_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'author'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return ['id','title','author','description','published_at','created_by','created_at','updated_at'];
    }
}
