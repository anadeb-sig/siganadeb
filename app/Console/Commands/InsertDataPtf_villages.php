<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AutomatiserController; 

class InsertDataPtf_villages extends Command
{
    protected $signature = 'insert:ptf_villages';

    protected $description = 'Insère les données dans la table ptf_villages';

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
        $controller->insertDataPtf_villages();
        //$this->info('Les données ont été insérées avec succès dans etatpaiements.');
    }
}
