<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImportRepasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataArray;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $dataArray, $userId)
    {
        $this->dataArray = $dataArray;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rowCount = 0;
        
        ini_set('max_execution_time', 36000); // 5 minutes

        foreach ($this->dataArray as $ligne)
        {
            $insertedId = DB::table('repas')->insertGetId([
                'user_id' => $this->userId,
                'menu_id' => $ligne["menu_id"],
                'effect_gar' => $ligne["effect_gar"],
                'effect_fil' => $ligne["effect_fil"],
                'date_rep' => $ligne["date_rep"],
                'inscrit_id' => $ligne["classe_id"]
            ]);

            if ($insertedId) {
                $rowCount++;
            }
        }

        // Logique pour notifier l'utilisateur ou traiter les erreurs si nécessaire
        // Par exemple : logger ou notifier par email
    }
}

