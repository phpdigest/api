<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmDefaultC31f75bfb3c90107422ec5a8d1384730 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('link_suggestion')
            ->addColumn('source', 'string', [
                'nullable' => true,
                'default'  => null,
                'size'     => 32
            ])
            ->update();

        $this->table('user_account')
            ->renameColumn('login', 'username')
            ->update();

        $this->table('user_identity')
            ->dropColumn('token')
            ->update();
    }

    public function down(): void
    {
        $this->table('identity')
            ->addColumn('token', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 128
            ])
            ->addIndex(["token"], [
                'name'   => 'identity_index_token_5f34458bf367f',
                'unique' => true
            ])
            ->update();

        $this->table('user_account')
            ->renameColumn('username', 'login')
            ->update();

        $this->table('link_suggestion')
            ->dropColumn('source')
            ->update();
    }
}
