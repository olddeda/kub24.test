<?php

use yii\db\Migration;

class m250919_143821_create_product_category_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%product_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-product_category-status', '{{%product_category}}', 'status');
        $this->createIndex('idx-product_category-name', '{{%product_category}}', 'name');
        $this->createIndex('idx-product_category-deleted_at', '{{%product_category}}', 'deleted_at');

        $this->addForeignKey(
            'fk-product_category-created_by',
            '{{%product_category}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-product_category-updated_by',
            '{{%product_category}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-product_category-deleted_by',
            '{{%product_category}}',
            'deleted_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-product_category-created_by', '{{%product_category}}');
        $this->dropForeignKey('fk-product_category-updated_by', '{{%product_category}}');
        $this->dropForeignKey('fk-product_category-deleted_by', '{{%product_category}}');
        
        $this->dropTable('{{%product_category}}');
    }
}
