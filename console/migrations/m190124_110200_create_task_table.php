<?php

use \yii\db\Migration;

class m190124_110200_create_task_table extends Migration
{
    public string $tableName = '{{%task}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
