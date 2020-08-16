<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmDefault185556e6f8caf503f3363d97fd0ffff2 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('link_suggestion')
            ->addColumn('id', 'primary', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('url', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('description', 'string', [
                'nullable' => true,
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
                'name'   => 'link_suggestion_index_identity_id_5f3911c14898c',
                'unique' => false
            ])
            ->addForeignKey(["identity_id"], 'identity', ["id"], [
                'name'   => 'link_suggestion_foreign_identity_id_5f3911c14899c',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('link_suggestion')->drop();
    }
}
