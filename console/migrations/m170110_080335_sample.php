<?php

use yii\db\Migration;

class m170110_080335_sample extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sample}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(120)->notNull(),
            'test_sheet_id' => $this->integer(),
            'user_id' => $this->integer(),
            'serial_number' => $this->string(60),
            'weight' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'unit' => $this->integer(5)->notNull(),
            'type' => $this->integer(5)->notNull(),
            'document' => $this->string(),
            'project_sn'=>$this->string(120),
            'comment' => $this->string(),
            'status' => $this->smallInteger(2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'completed_at' => $this->integer()->notNull()->defaultValue(0),
            'FOREIGN KEY (user_id) REFERENCES ' . '{{%user}}' . ' (id) ON DELETE SET NULL  ON UPDATE CASCADE',
            'FOREIGN KEY (test_sheet_id) REFERENCES ' . '{{%test_sheet}}' . ' (id) ON DELETE SET NULL  ON UPDATE CASCADE',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%sample}}');
    }
}
