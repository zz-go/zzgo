<?php

namespace ZZGo\Console;

use Illuminate\Console\Command;
use ZZGo\Generator\Constraint;
use ZZGo\Generator\Controller;
use ZZGo\Generator\Migration;
use ZZGo\Generator\Model;
use ZZGo\Generator\Resource;
use ZZGo\Models\SysDbTableDefinition;

/**
 * Class ZZGo
 *
 * @package App\Console\Commands
 */
class ZZGo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zz:go';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Materialize all ZZGO tables';

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
        $data_definitions = SysDbTableDefinition::all();

        foreach ($data_definitions as $data_definition) {
            (new Migration($data_definition))->materialize();
            (new Model($data_definition))->materialize();
            (new Controller($data_definition))->materialize();
            (new Resource($data_definition))->materialize();
        }

        //Add constraints as last step
        foreach ($data_definitions as $data_definition) {
            (new Constraint($data_definition))->materialize();
        }

        return;
    }
}
