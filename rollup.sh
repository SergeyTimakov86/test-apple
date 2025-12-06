#!/bin/sh

php init --env=Development --overwrite=All --delete=All
php yii migrate --interactive=0