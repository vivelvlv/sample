<?php

use yii\db\Migration;

class m170110_080730_sample_service_action extends Migration
{
   public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sample_service_action}}', [
            'id' => $this->primaryKey(5),
            'action_user'=>  $this->integer()->notNull(),
            'status'=>  $this->smallInteger(2)->notNull()->defaultValue(0),
            'type'=>  $this->smallInteger(1)->notNull()->defaultValue(1), //0-- user , 1-- admin-user
            'comment'=> $this->string(),
            'sample_service_id'=>  $this->integer()->notNull(),
            'created_at'=>  $this->integer()->notNull(),
            'FOREIGN KEY (sample_service_id) REFERENCES ' .'{{%sample_service}}'. ' (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%sample_service_action}}');
    }
}
