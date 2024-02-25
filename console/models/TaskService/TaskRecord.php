<?php

namespace console\models\TaskService;

use yii\db\Connection;


/**
 * @property int $id
 * @property int $status
 * @property int $type
 * @property int $created_at
 * @property int $updated_at
 */
class TaskRecord extends \yii\db\ActiveRecord
{
    public const IN_QUEUE = -1;
    public const IN_PROCESS = 1;
    public const SUCCESS = 2;
    public const ERROR = 3;

    public const TYPE_USER = 1;
    public const TYPE_PRODUCT = 2;
    public const TYPE_PAYMENT = 3;

    public const TYPES = [
        self::TYPE_USER,
        self::TYPE_PRODUCT,
        self::TYPE_PAYMENT,
    ];

    public static function tableName(): string
    {
        return '{{%api_request}}';
    }

    public static function getDb(): Connection
    {
        return \Yii::$app->db;
    }
}