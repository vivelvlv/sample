<?php

use yii\db\Migration;

class m170110_080131_article extends Migration
{
   public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%article}}', [
            'article_id' => $this->primaryKey(),
            'title' => $this->string(150)->notNull(),
            'description'=>$this->string(255),
            'is_show'=>$this->smallInteger(1)->notNull()->defaultValue(1),
            'content'=>$this->text(),      
            'type'=>$this->smallInteger(1)->notNull()->defaultValue(1),
            'created_at'=>$this->integer()->notNull(),
            'created_by'=>$this->integer(),
            'FOREIGN KEY (created_by) REFERENCES ' .'{{%admin_user}}'. ' (id) ON DELETE SET NULL',
        ], $tableOptions);    
    }

    public function safeDown()
    {
        $this->dropTable('{{%article}}');
    }
}
