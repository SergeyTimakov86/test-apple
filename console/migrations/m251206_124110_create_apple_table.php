<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m251206_124110_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'fell_at' => $this->integer()->null(),
            'state' => $this->integer()->notNull()->defaultValue(0),
            'eaten_percent' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
