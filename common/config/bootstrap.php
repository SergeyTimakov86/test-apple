<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

const TEST_USER_NAME = 'test';
const TEST_USER_EMAIL = 'test@test.test';
const TEST_USER_PASSWORD = 'test@test.test';