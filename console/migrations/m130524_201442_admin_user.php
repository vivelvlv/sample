<?php

use yii\db\Migration;

class m130524_201442_admin_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%admin_user}}', [
            'id' => $this->primaryKey(),
            'user_name' => $this->string()->notNull(),
            'work_no'=> $this->string(10)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'office_phone'=> $this->string(20),
            'mobile_phone'=> $this->string(20),
            'image'=> $this->string(),
            'leader_id'=>  $this->integer(),
            'lab_building'=> $this->string(20),
            'lab_floor'=> $this->string(20),
            'lab_room'=> $this->string(20),
            'office_building'=> $this->string(20),
            'office_floor'=> $this->string(20),
            'office_room'=> $this->string(20),
            'entry_date'=> $this->integer()->notNull(),
            'leave_date'=> $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'is_super'=>$this->smallInteger(1)->notNull()->defaultValue(0),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%admin_user}}');
    }
}
