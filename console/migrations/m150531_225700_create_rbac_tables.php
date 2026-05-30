<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use console\migrations\BaseMigration;
use yii\base\InvalidConfigException;
use yii\db\Schema;
use yii\rbac\DbManager;

/**
 * Initializes RBAC tables
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m150531_225700_create_rbac_tables extends BaseMigration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use before executing this migration.');
        }
        return $authManager;
    }

    public function up()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->beforeMigrateUp();

        $this->createTable($authManager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);
        $this->addPrimaryKey('pkey_auth_rule_name', $authManager->ruleTable, 'name');

        $this->createTable($authManager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);
        $this->addPrimaryKey('pkey_auth_item_name', $authManager->itemTable, 'name');
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');
        $this->addForeignKey('fkey_auth_item_rule_name', $authManager->itemTable, 'rule_name',
            $authManager->ruleTable, 'name', 'SET NULL', 'CASCADE'
        );

        $this->createTable($authManager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull()
        ], $this->tableOptions);
        $this->addPrimaryKey('pkey_auth_item_child_parent_child', $authManager->itemChildTable, [
            'parent',
            'child'
        ]);
        $this->addForeignKey('fkey_auth_item_child_parent', $authManager->itemChildTable, 'parent',
            $authManager->itemTable, 'name', 'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('fkey_auth_item_child_child', $authManager->itemChildTable, 'child',
            $authManager->itemTable, 'name', 'CASCADE', 'CASCADE'
        );

        $this->createTable($authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
        ], $this->tableOptions);
        $this->addPrimaryKey('pkey_auth_assignment_item_name_user_id', $authManager->assignmentTable, [
            'item_name',
            'user_id'
        ]);
        $this->addForeignKey('fkey_auth_assignment_item_name', $authManager->assignmentTable, 'item_name',
            $authManager->itemTable, 'name', 'CASCADE', 'CASCADE'
        );
        $this->addForeignKey('fkey_auth_assignment_user_id', $authManager->assignmentTable, 'user_id',
            '{{%user}}', 'id', 'CASCADE', 'CASCADE'
        );
    }

    public function down()
    {
        $authManager = $this->getAuthManager();
        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }
}
