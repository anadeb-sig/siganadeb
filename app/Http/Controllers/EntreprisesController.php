<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Exception;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

use DB;

use Auth;

class EntreprisesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:entreprise-create|entreprise-edit|entreprise-show|entreprise-destroy', ['only' => ['index']]);
        $this->middleware('permission:entreprise-index', ['only' => ['index']]);
        $this->middleware('permission:entreprise-create', ['only' => ['create','store']]);
        $this->middleware('permission:entreprise-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:entreprise-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:entreprise-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the entreprises.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $entreprises = Entreprise::paginate(25);

        return view('entreprises.index', compact('entreprises'));
    }

    public function fetch(){
        $entreprises = Entreprise::orderByDesc('entreprises.created_at')
                    ->get();
        return response()->json($entreprises);
    }

    /**
     * Show the form for creating a new entreprise.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('entreprises.create');
    }

    /**
     * Store a new entreprise in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $data['nom_charge'] = mb_strtoupper($request->nom_charge, 'UTF-8');
            
            $data['prenom_charge'] = mb_strtoupper($request->prenom_charge, 'UTF-8');
            
            $data['nom_entrep'] = mb_strtoupper($request->nom_entrep, 'UTF-8');
            
            $data['addr'] = mb_strtoupper($request->addr, 'UTF-8');

            Entreprise::create($data);

            return redirect()->route('entreprises.index')
                ->with('success_message', __('Enregistrement effectué avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('entreprises.unexpected_error')]);
        }
    }

    /**
     * Display the specified entreprise.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $entreprise = Entreprise::findOrFail($id);

        return response()->json($entreprise);
    }

    /**
     * Show the form for editing the specified entreprise.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        return response()->json($entreprise);
    }

    /**
     * Update the specified entreprise in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $entreprise = Entreprise::findOrFail($request->id);

            $data['nom_charge'] = mb_strtoupper($request->nom_charge, 'UTF-8');
            
            $data['prenom_charge'] = mb_strtoupper($request->prenom_charge, 'UTF-8');
            
            $data['nom_entrep'] = mb_strtoupper($request->nom_entrep, 'UTF-8');
            
            $data['addr'] = mb_strtoupper($request->addr, 'UTF-8');
            
            $entreprise->update($data);

            return redirect()->route('entreprises.index')
                ->with('success_message', __('Modification effectuée avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('entreprises.unexpected_error')]);
        }        
    }

    /**
     * Remove the specified entreprise from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $entreprise = Entreprise::findOrFail($id);
            $entreprise->delete();

            return redirect()->route('entreprises.index')
                ->with('success_message', __('Suppression effectuée avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('entreprises.unexpected_error')]);
        }
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            'nom_entrep' => 'required|string',
            'num_id_f' => 'required|string|min:4|max:50',
            'nom_charge' => 'required|string',
            'prenom_charge' => 'required|string',
            'email' => 'required|string',
            'tel' => 'required|integer|digits:8',
            'addr' => 'string', 
        ];

        
        $data = $request->validate($rules);




        return $data;
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

        $lignes = [];

        $lignes_excel = [];

        foreach($data as $i => $row){

            $details = explode(";", $row[0]);

            $verif = new \App\Models\Entreprise;
            $verification = $verif->si_existe($details[5]);

            if (count($verification) == 0) {
                $lignes = [
                    "nom_entrep" => $details[0],
                    "num_id_f" => $details[5],
                    "nom_charge" => $details[1],
                    "prenom_charge" => $details[2],
                    "email" => $details[3],
                    "tel" => $details[4],
                    "addr" => $details[6]
                ];

                $lignes_excel[] = $lignes;

                $lignes = [];
            }
        }

        if (!empty($lignes_excel)) {
            $rowCount = 0;
            foreach ($lignes_excel as $value) {
                $insertedId = DB::table('entreprises')->insertGetId([
                    "nom_entrep" => $value["nom_entrep"],
                    "num_id_f" => $value["num_id_f"],
                    "nom_charge" => $value["nom_charge"],
                    "prenom_charge" => $value["prenom_charge"],
                    "email" => $value["email"],
                    "tel" => $value["tel"],
                    "addr" => $value["addr"]
                ]);

                if ($insertedId) {
                    $rowCount++;
                }
            }
            return redirect()->route('entreprises.index')
            ->with('success_message', $rowCount.' enregistrements chargés avec succès');
        
        }else {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }
}
