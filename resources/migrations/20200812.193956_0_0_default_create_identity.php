<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmDefault1869de4834ddb9e41dc60b3923ec3fdc extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('identity')
            ->addColumn('id', 'primary', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('token', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 128
            ])
            ->addColumn('created_at', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('updated_at', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addIndex(["token"], [
                'name'   => 'identity_index_token_5f34458bf367f',
                'unique' => true
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('identity')->drop();
    }
}
