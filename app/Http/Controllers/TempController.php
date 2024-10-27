<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\ImportRepasJob;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

class TempController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function charger(Request $request)
    // {
    //     try {

    //         $dataArray = $request->input('data');
    //         $rowCount = 0;
            
    //         //ini_set('max_execution_time', 36000); // 5 minutes
            
    //         foreach ($dataArray as $ligne) {
    //             $insertedId = DB::table('repas')->insertGetId([
    //                 'user_id' => Auth::user()->id,
    //                 'menu_id' => $ligne["menu_id"],
    //                 'effect_gar' => $ligne["effect_gar"],
    //                 'effect_fil' => $ligne["effect_fil"],
    //                 'date_rep' => $ligne["date_rep"],
    //                 'inscrit_id' => $ligne["classe_id"]
    //             ]);
    //             if ($insertedId) {
    //                 $rowCount++;
    //             }
    //         }

    //         if ($rowCount > 0) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => $rowCount . ' plats importés avec succès!',
    //                 'redirect_url' => url('/repas')
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'error' => true,
    //                 'message' => 'Aucune donnée n\'est chargée.'
    //             ], 400);  // Utilise un code d'erreur HTTP approprié.
    //         }
    //     } catch (\Exception $e) {
    //         // Renvoie une réponse JSON en cas d'erreur serveur
    //         return response()->json([
    //             'error' => true,
    //             'message' => 'Erreur serveur : ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function charger(Request $request)
    {
        try 
        {

            $dataArray = $request->input('data');
            // Décoder le JSON
            $decodedData = json_decode($dataArray, true);

            // Récupération de l'ID de l'utilisateur connecté
            $userId = Auth::user()->id;

            // Dispatcher le job pour gérer l'importation en arrière-plan
            ImportRepasJob::dispatch($decodedData, $userId);

            return redirect()->route('repas.index')
            ->with('success_message', 'Importation a été effectuée avec succès.');
        } catch (Exception $exception) {
            return  redirect()->route('repas.index')
            ->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        ini_set('max_execution_time', 36000); // 5 minutes

        $file = $request->file('file');
        $filename = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads', $filename);

        // Stocker le chemin du fichier dans une table temporaire
        DB::table('temps')->insertGetId(['user_id' => Auth::user()->id, 'file' => $filename, 'path' => $path]);

        // Récupérer le chemin du fichier depuis la table temporaire
        $tempFile = DB::table('temps')->latest('id')->first();

        $filepath = storage_path('app/' . $tempFile->path);
        
        // Traitement du fichier CSV
        $handle = fopen($filepath, 'r');
        $header = fgetcsv($handle, 1000, ',');
        $data = [];
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $data[] = $row;
        }

        fclose($handle);
        
        return view('temps.index', compact('data'));
    }
}
