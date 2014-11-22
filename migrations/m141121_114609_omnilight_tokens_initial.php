<?php

use yii\db\Migration;
use yii\db\Schema;

class m141121_114609_omnilight_tokens_initial extends Migration
{
    public function up()
    {
        $this->createTable('{{%tokens}}', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_STRING,
            'name' => Schema::TYPE_STRING,
            'token' => Schema::TYPE_STRING.'(32)',
            'created_at' => Schema::TYPE_DATETIME,
            'expire_at' => Schema::TYPE_DATETIME,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tokens}}');
    }
}
