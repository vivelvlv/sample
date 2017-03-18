<?php

use yii\db\Migration;

class m170110_080747_complaint extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%complaint}}', [
            'id' => $this->primaryKey(5),
            'title' => $this->string(150)->notNull(),
            'user_id'=>  $this->integer()->notNull(),
            'sample_service_id'=> $this->integer()->notNull(),
            'content'=> $this->string(),
            'action_user'=>  $this->integer(),
            'feedback'=> $this->string(),
            'created_at' => $this->integer()->notNull(),
            'feedback_at' => $this->integer()->notNull(),
            'status'=>  $this->smallInteger(2)->notNull()->defaultValue(0),
            'FOREIGN KEY (sample_service_id) REFERENCES ' .'{{%sample_service}}'. ' (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%complaint}}');
    }
}
