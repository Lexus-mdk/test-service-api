<?php

namespace console\models\ApiRequestService;

use yii\db\Connection;

/**
 * @property int $id
 * @property int $task_id
 * @property string $request_id
 */
class ApiRequestRecord extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%api_request}}';
    }

    public static function getDb(): Connection
    {
        return \Yii::$app->db;
    }
}