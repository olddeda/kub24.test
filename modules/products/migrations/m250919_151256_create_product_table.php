<?php

use yii\db\Migration;

class m250919_151256_create_product_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'price' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'category_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'deleted_at' => $this->timestamp()->null(),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-product-status', '{{%product}}', 'status');
        $this->createIndex('idx-product-category_id', '{{%product}}', 'category_id');
        $this->createIndex('idx-product-name', '{{%product}}', 'name');
        $this->createIndex('idx-product-price', '{{%product}}', 'price');
        $this->createIndex('idx-product-deleted_at', '{{%product}}', 'deleted_at');

        $this->addForeignKey(
            'fk-product-category_id',
            '{{%product}}',
            'category_id',
            '{{%product_category}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-product-created_by',
            '{{%product}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-product-updated_by',
            '{{%product}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-product-deleted_by',
            '{{%product}}',
            'deleted_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-product-category_id', '{{%product}}');
        $this->dropForeignKey('fk-product-created_by', '{{%product}}');
        $this->dropForeignKey('fk-product-updated_by', '{{%product}}');
        $this->dropForeignKey('fk-product-deleted_by', '{{%product}}');
        
        $this->dropTable('{{%product}}');
    }
}
