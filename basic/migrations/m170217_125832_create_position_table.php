<?php

use yii\db\Migration;

/**
 * Handles the creation of table `position`.
 */
class m170217_125832_create_position_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%position}}', [
            'id' => $this->primaryKey(),
            'domain_id' => $this->integer(),
            'object' => $this->string(),
            'object_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%position}}');
    }
       
}
