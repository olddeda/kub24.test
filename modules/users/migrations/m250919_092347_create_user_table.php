<?php

use yii\db\Migration;

class m250919_092347_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-user-status', '{{%user}}', 'status');
        $this->createIndex('idx-user-created_at', '{{%user}}', 'created_at');
        $this->createIndex('idx-user-deleted_at', '{{%user}}', 'deleted_at');

        $this->addForeignKey(
            'fk-user-created_by',
            '{{%user}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-user-updated_by',
            '{{%user}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-user-deleted_by',
            '{{%user}}',
            'deleted_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user-created_by', '{{%user}}');
        $this->dropForeignKey('fk-user-updated_by', '{{%user}}');
        $this->dropForeignKey('fk-user-deleted_by', '{{%user}}');
        
        $this->dropTable('{{%user}}');
    }
}
