<?php

namespace ZZGo\Console;

use Illuminate\Console\Command;
use ZZGo\Seeds\SysDbSeeder;

/**
 * Class ZZGo
 *
 * @package App\Console\Commands
 */
class ZZSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zz:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed example data to DB definition tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @throws \Exception
     */
    public function handle()
    {
        $seeder = new SysDbSeeder();
        $seeder->run();

        return;
    }
}
