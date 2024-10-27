<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AutomatiserController;

class Outil_ptf_village extends Command
{
    protected $signature = 'insert:etat_ptf_village';

    protected $description = 'Insère les données dans la table etat_ptf_village';

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
        $controller->outil_ptf_village();
        //$this->info('Les données ont été insérées avec succès dans etatpaiements.');
    }
}
