<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Typeouvrage;
use Illuminate\Http\Request;
use Exception;

class TypeouvragesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:typeouvrage-create|typeouvrage-edit|typeouvrage-show|typeouvrage-destroy', ['only' => ['index']]);
        $this->middleware('permission:typeouvrage-index', ['only' => ['index']]);
        $this->middleware('permission:typeouvrage-create', ['only' => ['create','store']]);
        $this->middleware('permission:typeouvrage-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:typeouvrage-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:typeouvrage-show', ['only' => ['show']]);
    }
    /**
     * Display a listing of the typeouvrages.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $typeouvrages = Typeouvrage::paginate(25);

        return view('typeouvrages.index', compact('typeouvrages'));
    }

    public function fetch(){
        $typeouvrages = Typeouvrage::orderByDesc('typeouvrages.created_at')
                    ->get();
        return response()->json($typeouvrages);
    }
    /**
     * Show the form for creating a new 
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('typeouvrages.create');
    }

    /**
     * Store a new typeouvrage in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            $data['nom_type'] = mb_strtoupper($request->nom_type, 'UTF-8');

            Typeouvrage::create($data);

            return redirect()->route('typeouvrages.index')
                ->with('success_message', __('Type d\'ouvrage ajouté avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('typeouvrages.unexpected_error')]);
        }
    }

    /**
     * Display the specified 
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $typeouvrage = Typeouvrage::findOrFail($id);
        return response()->json($typeouvrage);
    }

    /**
     * Show the form for editing the specified 
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $typeouvrage = Typeouvrage::findOrFail($id);     

        return response()->json($typeouvrage);
    }

    /**
     * Update the specified typeouvrage in the storage.
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
            
            $data['nom_type'] = mb_strtoupper($request->nom_type, 'UTF-8');
                        
            $typeouvrage = Typeouvrage::findOrFail($request->id);
            $typeouvrage->update($data);

            return redirect()->route('typeouvrages.index')
                ->with('success_message', __('Type d\'ouvrage modifié avec succès'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('typeouvrages.unexpected_error')]);
        }        
    }

    /**
     * Remove the specified typeouvrage from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $typeouvrage = Typeouvrage::findOrFail($id);
            $typeouvrage->delete();

            return redirect()->route('typeouvrages.index')
                ->with('success_message', __('Type d\'ouvrage supprimé avec succès'));
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => trans('typeouvrages.unexpected_error')]);
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
            'nom_type' => 'required|string',
        ];
        $data = $request->validate($rules);

        return $data;
    }

}
