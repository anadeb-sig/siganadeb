<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Exception;
use Validator;
use DB;

class MenusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:menu-create|menu-edit|menu-show|menu-destroy', ['only' => ['index']]);
        $this->middleware('permission:menu-index', ['only' => ['index']]);
        $this->middleware('permission:menu-create', ['only' => ['create','store']]);
        $this->middleware('permission:menu-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:menu-destroy', ['only' => ['destroy']]);
        $this->middleware('permission:menu-show', ['only' => ['show']]);
        $this->middleware('permission:menu-statut', ['only' => ['updateStatut']]);
    }

    /**
     * Display a listing of the menus.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('menus.index');
    }

    public function fetch(){
        $menus = Menu::orderByDesc('created_at')
                    ->get();
        return response()->json($menus);
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('menus.create');
    }

    /**
     * Store a new menu in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            
            $data = $this->getData($request);
            
            $data['descri'] = mb_strtoupper($request->descri, 'UTF-8');
            
            Menu::create($data);

            return redirect()->route('menus.index')
            ->with('success_message', 'Menu a été ajouté avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

    /**
     * Display the specified menu.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $menu = Menu::findOrFail($id);

        return response()->json($menu);
    }

    /**
     * Show the form for editing the specified menu.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json($menu);
    }

    /**
     * Update the specified menu in the storage.
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
            
            $menu = Menu::findOrFail($request->id);

            $data['descri'] = mb_strtoupper($request->descri, 'UTF-8');

            $menu->update($data);

            return redirect()->route('menus.index')
            ->with('success_message', 'Menu a été modifié avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }        
    }

    /**
     * Remove the specified menu from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            return redirect()->route('menus.index')
            ->with('success_message', 'Menu a été supprimé avec succès.');
        } catch (Exception $exception){
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
            'descri' => 'nullable|string',
            'cout_unt' => 'required|numeric'
        ];
        $data = $request->validate($rules);
        return $data;
    }

    public function updateStatut($id, $statut)
    {
        // Validation
        $validate = Validator::make([
            'id'   => $id,
            'statut'    => $statut
        ], [
            'id'   =>  'required|exists:menus,id',
            'statut'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('menus.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            Menu::query()->update(['statut' => '0']);

            Menu::whereId($id)->update(['statut' => $statut]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('menus.index')->with('success_message','Statut modifié avec succès!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            //DB::rollBack();
            return redirect()->back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }

}
