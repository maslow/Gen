<?php

use yii\db\Migration;

/**
 * Handles the creation for table `administrator`.
 */
class m160511_084521_create_administrator extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%administrator}}', [
            'uid' => $this->integer()->notNull(),
            'username' => $this->string(32)->unique()->notNull(),
            'password' => $this->string(64)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_ip' => $this->string(16)->notNull(),
            'created_by' => $this->integer()->notNull()
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%administrator}}');
    }
}
