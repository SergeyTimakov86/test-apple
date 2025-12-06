<?php

use common\models\User;
use yii\db\Migration;

class m251206_095924_test_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Лучше бы так конечно не делать, но для теста - сойдет

        $user = new User();
        $user->username = TEST_USER_NAME;
        $user->email = TEST_USER_EMAIL;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword(TEST_USER_PASSWORD);
        $user->generateAuthKey();
        $user->save();
    }
}
