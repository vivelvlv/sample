<?php

use yii\db\Migration;

class m170110_080117_test_sheet extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%test_sheet}}', [
            'id' => $this->primaryKey(),
            'name'=>  $this->string(120)->notNull(),
            'user_id'=>$this->integer()->notNull(),
            'storage_condition'=>$this->smallInteger(1),
            'service_type'=>$this->smallInteger(1),
            'report_fetch_type'=>$this->smallInteger(1),
            'sample_handle_type'=>$this->smallInteger(1),
            'status'=>  $this->smallInteger(2)->notNull()->defaultValue(0),
            'comment'=> $this->string(),
            'barcode'=> $this->string(120)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'completed_at' => $this->integer()->notNull()->defaultValue(0),
            'FOREIGN KEY (user_id) REFERENCES ' .'{{%user}}'. ' (id) ON DELETE CASCADE',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%test_sheet}}');
    }
}
