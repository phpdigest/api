<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmDefault818b1c7a77afc2311870e233d5ee0b15 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('link_suggestion')
            ->alterColumn('description', 'text', [
                'nullable' => true,
                'default'  => null
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('link_suggestion')
            ->alterColumn('description', 'string', [
                'nullable' => true,
                'default'  => null,
                'size'     => 255
            ])
            ->update();
    }
}
