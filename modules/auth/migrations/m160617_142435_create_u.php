<?php

use yii\db\Migration;

/**
 * Handles the creation for table `u`.
 */
class m160617_142435_create_u extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%u}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(64)->notNull(),
            'source' => $this->string(16)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%u}}');
    }
}
