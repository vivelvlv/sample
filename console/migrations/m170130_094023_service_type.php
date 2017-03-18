<?php

use yii\db\Migration;

class m170130_094023_service_type extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%service_type}}', [
            'id' => $this->primaryKey(5),
            'name' => $this->string(60)->notNull(),
            'description' => $this->string(),
            'is_show' => $this->smallInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%service_type}}');
    }
}
