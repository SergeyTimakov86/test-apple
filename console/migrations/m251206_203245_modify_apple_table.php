<?php

use yii\db\Migration;

class m251206_203245_modify_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%apple}}', 'size', $this->decimal(5, 2)->notNull()->defaultValue(1));
        $this->dropColumn('{{%apple}}', 'eaten_percent');
    }
}
