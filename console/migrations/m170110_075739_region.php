<?php

use yii\db\Migration;

class m170110_075739_region extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
     
        //create country table
        $this->createTable('{{%region_country}}', [
            'country_id' => $this->primaryKey(),
            'country_name' => $this->string()->notNull(),
            'area_code' => $this->string(6),
        ], $tableOptions);  

        //create province table
        $this->createTable('{{%region_province}}', [
            'province_id' => $this->primaryKey(),
            'province_name' => $this->string()->notNull(),
            'area_code' => $this->string(6),
            'country_id'=>$this->integer()->defaultValue(0),
            'FOREIGN KEY (country_id) REFERENCES ' .'{{%region_country}}'. ' (country_id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);  


        //create city table
        $this->createTable('{{%region_city}}', [
            'city_id' => $this->primaryKey(),
            'city_name' => $this->string()->notNull(),
            'area_code' => $this->string(6),
            'province_id'=>$this->integer()->defaultValue(0),
            'FOREIGN KEY (province_id) REFERENCES ' .'{{%region_province}}'. ' (province_id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);  

        //create area table
        $this->createTable('{{%region_area}}', [
            'area_id' => $this->primaryKey(),
            'area_name' => $this->string()->notNull(),
            'area_code' => $this->string(6),
            'city_id'=>$this->integer()->defaultValue(0),
            'FOREIGN KEY (city_id) REFERENCES ' .'{{%region_city}}'. ' (city_id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);  
    }

    public function safeDown()
    {
        $this->dropTable('{{%region_area}}');
        $this->dropTable('{{%region_city}}');
        $this->dropTable('{{%region_province}}');
        $this->dropTable('{{%region_country}}');
    }
}
