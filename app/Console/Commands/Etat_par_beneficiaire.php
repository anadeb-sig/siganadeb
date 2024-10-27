<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AutomatiserController; 

class Etat_par_beneficiaire extends Command
{
    protected $signature = 'insert:etat_par_beneficiaires';

    protected $description = 'Insère les données dans la table etat_par_beneficiaires';

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
        $controller->etat_par_beneficiaire();
        //$this->info('Les données ont été insérées avec succès dans etatpaiements.');
    }
}
