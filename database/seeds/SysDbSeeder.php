<?php

namespace ZZGo\Seeds;

use Illuminate\Database\Seeder;

class SysDbSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SysDbTableDefinitionSeeder::class);
        $this->call(SysDbFieldDefinitionSeeder::class);
    }
}
