<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class InstallRolesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:roles-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Populates the 'roles' table with basic role data";

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
        $result = DB::table('roles')->insert([['name' => 'OWNER', 'can_aprove' => true, 'can_delete' => true],
                                              ['name' => 'COLLABORATOR', 'can_aprove' => true, 'can_delete' => false]]);
        
        if ($result)
            $this->info("[SUCCESS]: $this->signature");
        else
            $this->error("[ERROR]: $this->signature");
    }
}
