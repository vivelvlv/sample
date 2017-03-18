<?php

use yii\db\Migration;

class m170110_080333_service extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%service}}', [
            'id' => $this->primaryKey(5),
            'catalog_number'=> $this->string(20)->notNull(),
            'name'=>  $this->string(60)->notNull(),
            'description'=> $this->string(),
            'price'=>$this->decimal(10,2)->notNull()->defaultValue(0.00),          
            'created_at' => $this->integer()->notNull(),
            'device_id'=> $this->integer(5),
            'comment'=> $this->string(),
            'type' => $this->integer(5)->notNull(),
            'is_show'=>  $this->smallInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%service}}');
    }
}
