<?php

use yii\db\Migration;

/**
 * Handles the creation of table `domain`.
 */
class m170215_062012_create_domain_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%domain}}', [
            'id' => $this->primaryKey(),
            'domain' => $this->string(),
            'login' => $this->string(),
            'pass' => $this->string(),
            'description' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%domain}}');
    }
}
