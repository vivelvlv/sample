<?php

use yii\db\Migration;

class m170110_080456_sample_service extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sample_service}}', [
            'id' => $this->primaryKey(),
            'sample_id'=> $this->integer(),
            'service_id'=> $this->integer(),
            'test_sheet_id'=> $this->integer(),
            'action_user'=> $this->integer(),
            'document'=>$this->string(),
            'barcode'=>$this->string(120),
            'created_at' => $this->integer()->notNull(),
            'received_at' => $this->integer()->notNull()->defaultValue(0),
            'completed_at' => $this->integer()->notNull()->defaultValue(0),
            'status'=>  $this->smallInteger(2)->notNull()->defaultValue(0),
            'user_id'=> $this->integer(),
            'FOREIGN KEY (action_user) REFERENCES ' .'{{%admin_user}}'. ' (id) ON DELETE SET NULL  ON UPDATE CASCADE',
            'FOREIGN KEY (sample_id) REFERENCES ' .'{{%sample}}'. ' (id) ON DELETE SET NULL  ON UPDATE CASCADE',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%sample_service}}');
    }
}
