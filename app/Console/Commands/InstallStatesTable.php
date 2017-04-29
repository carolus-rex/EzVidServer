<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class InstallStatesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:states-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Populates the 'states' table with valid vid states data";

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = DB::table('states')->insert([['idstate' => 0, 'name' => 'UNCHEKED'],
                                               ['idstate' => 1, 'name' => 'CHEKED'],
                                               ['idstate' => 2, 'name' => 'APROVED']]);
        
        if ($result)
            $this->info("[SUCCESS]: $this->signature");
        else
            $this->error("[ERROR]: $this->signature");
    }
}
