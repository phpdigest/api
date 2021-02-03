<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmDefault20a08f747a3a55db85a005ee6aa5b299 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('link_url')
            ->addColumn('id', 'primary', [
                'nullable' => false,
            ])
            ->addColumn('scheme', 'string', [
                'nullable' => false,
                'default'  => 'https',
                'size'     => 10
            ])
            ->addColumn('host', 'string', [
                'nullable' => false,
                'size'     => 64
            ])
            ->addColumn('path', 'string', [
                'nullable' => false,
                'default'  => '',
                'size'     => 255
            ])
            ->addColumn('query', 'string', [
                'nullable' => false,
                'default'  => '',
                'size'     => 255
            ])
            ->addColumn('created_at', 'datetime', [
                'nullable' => false,
            ])
            ->addColumn('updated_at', 'datetime', [
                'nullable' => false,
            ])
            ->addIndex(["host", "path", "query"], [
                'name'   => 'link_url_index_host_path_query_60180143ba089',
                'unique' => true
            ])
            ->setPrimaryKeys(["id"])
            ->create();

        $this->database()->table('link_suggestion')->delete()->run();

        $this->table('link_suggestion')
            ->dropColumn('url')
            ->update();

        $this->table('link_suggestion')
            ->addColumn('url_id', 'integer', [
                'nullable' => false,
            ])
            ->addForeignKey(["url_id"], 'link_url', ["id"], [
                'name'   => 'link_suggestion_foreign_url_id_6013ee8d0af3a',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('link_suggestion')
            ->dropForeignKey(["url_id"])
            ->dropColumn('url_id')
            ->addColumn('url', 'string', [
                'nullable' => false,
                'default' => null,
                'size' => 255,
            ])
            ->update();

        $this->table('link_url')->drop();
    }
}
