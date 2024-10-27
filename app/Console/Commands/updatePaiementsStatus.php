<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AutomatiserController;

class updatePaiementsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:paiements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modifier les painding acount dans la table de paiement';

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
        $controller->updatePaiementsStatus();
        //$this->info('Les données ont été modifiées avec succès dans la table de paiements.');
    }
}
