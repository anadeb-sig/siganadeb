<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AutomatiserController; 

class Contrat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:contrat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new AutomatiserController(); // Remplacez par le nom du contrôleur approprié
        $controller->contrat();
    }
}
