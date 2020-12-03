<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmDefaultDfba11ba00776b339522e1ca16a07c02 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('account')->rename('user_account');
        $this->table('identity')->rename('user_identity');
        $this->table('user_token')
            ->addColumn('id', 'primary', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('token', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('type', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->addColumn('created_at', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('identity_id', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addIndex(["identity_id"], [
                'name'   => 'user_token_index_identity_id_5fc5444109892',
                'unique' => false
            ])
            ->addIndex(["type", "token"], [
                'name'   => 'user_token_index_type_token_5fc544410a748',
                'unique' => true
            ])
            ->addForeignKey(["identity_id"], 'user_identity', ["id"], [
                'name'   => 'user_token_foreign_identity_id_5fc54441098d1',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('user_token')->drop();
        $this->table('user_account')->rename('account');
        $this->table('user_identity')->rename('identity');
    }
}
