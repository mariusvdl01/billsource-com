<?php
/**
 * Created by PhpStorm.
 * User: kenny
 * Date: 5/9/17
 * Time: 1:20 AM
 */

namespace console\migrations;

use yii\db\Migration;

class BaseMigration extends Migration
{
    protected $tableOptions = null;

    public function beforeMigrateUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->execute("SET SQL_MODE = ''");
    }

    public function beforeMigrateDown()
    {
        $this->execute("SET SQL_MODE = ''");
    }
}