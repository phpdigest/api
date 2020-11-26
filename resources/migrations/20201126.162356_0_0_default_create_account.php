<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmDefault36665df21ef7d8c1da3abc3e622e24ab extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('account')
            ->addColumn('id', 'primary', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('login', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 48
            ])
            ->addColumn('password_hash', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('created_at', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('updated_at', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('identity_id', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addIndex(["identity_id"], [
                'name'   => 'account_index_identity_id_5fbfd69be6671',
                'unique' => true
            ])
            ->addIndex(["login"], [
                'name'   => 'account_index_login_5fbfd69be6bd7',
                'unique' => true
            ])
            ->addForeignKey(["identity_id"], 'identity', ["id"], [
                'name'   => 'account_foreign_identity_id_5fbfd69be66b0',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('account')->drop();
    }
}
