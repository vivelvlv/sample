<?php

use yii\db\Migration;

class m170110_080040_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'user_name' => $this->string(60)->notNull(),
            'mobile_phone' => $this->string(20)->unique(),
            'email' => $this->string(60),
            'province' => $this->integer(),
            'city' => $this->integer(),
            'district' => $this->integer(),
            'detail_address' => $this->string(255),
            'prefix' => $this->string(20),
            'company_name' => $this->string(120),
            'company_tax' => $this->string(60),
            'last_login' => $this->integer()->notNull()->defaultValue(0),
            'visit_count' => $this->smallInteger(5)->notNull()->defaultValue(0),
            'is_validated' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'is_super' => $this->smallInteger(1)->notNull()->defaultValue(0),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
