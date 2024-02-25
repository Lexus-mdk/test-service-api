<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%api_response}}`.
 */
class m240225_160336_create_api_response_table extends Migration
{
    public string $tableName = '{{%api_response}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'response' => $this->string()->notNull()->defaultValue(''),
            'request_id' => $this->integer()->notNull(),
            'parent_response_id' => $this->integer()->null(),
        ]);

        $this->addForeignKey('fk_request_id_to_api_request',
            $this->tableName,
            'request_id',
            'api_request',
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
