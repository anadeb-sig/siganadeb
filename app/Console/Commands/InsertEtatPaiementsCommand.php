<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AutomatiserController; 

class InsertEtatPaiementsCommand extends Command
{
    protected $signature = 'insert:etatpaiements';

    protected $description = 'Insère les données dans la table etatpaiements';

    public function __construct()
    {
        parent::__construct();
    }
    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new AutomatiserController(); // Remplacez par le nom du contrôleur approprié
        $controller->insertEtatPaiements();
        //$this->info('Les données ont été insérées avec succès dans etatpaiements.');
    }
}
