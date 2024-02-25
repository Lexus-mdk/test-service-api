<?php

namespace console\models\ApiResponseService;

use yii\db\Connection;

/**
 * @property int $id
 * @property int $request_id
 * @property string $response
 * @property ?int $parent_response_id
 */
class ApiResponseRecord extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%api_response}}';
    }

    public static function getDb(): Connection
    {
        return \Yii::$app->db;
    }
}