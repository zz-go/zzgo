<?php

namespace ZZGo;

use Illuminate\Console\Command;
use ZZGo\Controller;
use ZZGo\Migration;
use ZZGo\Model;

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
    protected $description = 'Command description';

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
        $this->createMigration("chair");
        $this->createModel("chair");
        $this->createController("chair");

        $this->createMigration("table");
        $this->createModel("table");
        $this->createController("table");

        return;
    }


    /**
     * @param $tableName
     * @throws \Exception
     */
    protected function createMigration($tableName)
    {
        $migration = new Migration($tableName);
        $migration->addField(["name" => "id", "type" => "bigIncrements"]);
        $migration->addField(["name" => "test", "type" => "integer", "index" => true, "unsigned" => true]);
        $migration->addField(["name" => "xxx1", "type" => "string", "index" => true, "default" => "xx"]);
        $migration->addField(["name" => "xxx2", "type" => "integer", "index" => true, "default" => 1]);
        $migration->addField(["name" => "xxx3", "type" => "boolean", "index" => true, "default" => false]);
        $migration->addField(["name" => "xxx4", "type" => "float", "index" => true, "default" => 1.111]);
        $migration->addFunction("timestamps");
        $migration->addFunction("softDeletes");
        $migration->materialize();
    }


    /**
     * @param $modelName
     */
    protected function createModel($modelName)
    {
        $model = new Model($modelName);
        $model->setFillable(["test", "xxx1"]);
        $model->setUseSoftDeletes();
        $model->materialize();
    }


    /**
     * @param $modelName
     */
    protected function createController($modelName)
    {
        $model = new Controller($modelName);
        $model->materialize();
    }
}
