<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%api_request}}`.
 */
class m240225_160327_create_api_request_table extends Migration
{
    public string $tableName = '{{%api_request}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'request_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_task_id_to_task',
            $this->tableName,
            'task_id',
            'task',
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
