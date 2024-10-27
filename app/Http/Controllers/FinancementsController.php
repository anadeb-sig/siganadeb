<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Financement;
use Illuminate\Http\Request;
use Exception;

class FinancementsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('permission:financement-create|financement-edit|financement-show|financement-destroy', ['only' => ['index']]);
        $this->middleware('permission:financement-index', ['only' => ['index']]);
        $this->middleware('permission:financement-create', ['only' => ['create','store']]);
        $this->middleware('permission:financement-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:financement-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:financement-show', ['only' => ['show']]);
    }

    /**
     * Display a listing of the financements.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $financements = Financement::paginate(25);

        return view('financements.index', compact('financements'));
    }

    public function fetch(){
        $financements = Financement::orderByDesc('created_at')
                    ->get();
        return response()->json($financements);
    }

    /**
     * Show the form for creating a new financement.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('financements.create');
    }

    /**
     * Store a new financement in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $data['nom_fin'] = mb_strtoupper($request->nom_fin, 'UTF-8');
            $data['commentaire'] = mb_strtoupper($request->commentaire, 'UTF-8');

            Financement::create($data);

            return redirect()->route('financements.index')
                ->with('success_message', 'Financement a été ajouté avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified financement.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $financement = Financement::findOrFail($id);
        return response()->json($financement);
    }

    /**
     * Show the form for editing the specified financement.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Financement $financement)
    {
        return response()->json($financement);
    }

    /**
     * Update the specified financement in the storage.
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
            
            $financement = Financement::findOrFail($request->id);
            
            $data['nom_fin'] = mb_strtoupper($request->nom_fin, 'UTF-8');
            $data['commentaire'] = mb_strtoupper($request->commentaire, 'UTF-8');

            $financement->update($data);

            return redirect()->route('financements.index')
                            ->with('success_message', 'Financement a été modifié avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }        
    }

    /**
     * Remove the specified financement from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Financement $financement)
    {
        try {
            $financement->delete();

            return redirect()->route('financements.index')
            ->with('success_message', 'Financement a été supprimé avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
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
            'nom_fin' => 'required|string|min:0|max:150',
            'commentaire' => 'required', 
        ];
        $data = $request->validate($rules);
        return $data;
    }

}
