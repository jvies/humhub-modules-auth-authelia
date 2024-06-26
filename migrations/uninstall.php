<?php

use humhub\modules\user\models\Group;
use yii\db\Migration;

class uninstall extends Migration
{

    public function up()
    {
        $tableSchema = Yii::$app->getDb()->getSchema()->getTableSchema(Group::tableName(), true);
        if (in_array('authelia_id', $tableSchema->columnNames, true)) {
            $this->dropColumn('{{%group}}', 'authelia_id');
        }
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
