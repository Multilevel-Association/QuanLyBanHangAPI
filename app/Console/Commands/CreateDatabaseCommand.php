<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database {dbname} {connection?}';
        /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
     try{
         $dbname = $this->argument('dbname');
         $connection = $this->hasArgument('connection') && $this->argument('connection') ? $this->argument('connection'): DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);

         $hasDb = DB::connection($connection)->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "."'".$dbname."'");

         if(empty($hasDb)) {
             DB::connection($connection)->select('CREATE DATABASE '. $dbname);
             $this->info("Database '$dbname' created for '$connection' connection");
         }
         else {
             $this->info("Database $dbname already exists for $connection connection");
         }
     }
     catch (\Exception $e){
         $this->error($e->getMessage());
     }
    }
    protected $description = 'Create new database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


}
