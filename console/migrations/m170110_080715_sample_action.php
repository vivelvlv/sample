<?php

use yii\db\Migration;

class m170110_080715_sample_action extends Migration
{
   public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sample_action}}', [
            'id' => $this->primaryKey(5),
            'action_user'=>  $this->integer()->notNull(),
            'type'=>  $this->smallInteger(1)->notNull()->defaultValue(1), //0-- user , 1-- admin-user
            'status'=>  $this->smallInteger(2)->notNull()->defaultValue(0),
            'comment'=> $this->string(),
            'sample_id'=>  $this->integer()->notNull(),
            'created_at'=>  $this->integer()->notNull(),
            'FOREIGN KEY (sample_id) REFERENCES ' .'{{%sample}}'. ' (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%sample_action}}');
    }
}
