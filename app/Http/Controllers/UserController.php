<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Auth;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create','store', 'updateStatus']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }


    /**
     * List User 
     * @param Nill
     * @return Array $user
     * @author Shani Singh
     */
    public function index()
    {
        //$users = User::with('roles')->paginate(10);
        return view('users.index');
    }

    public function fetch()
    {
        $users = User::join('roles', 'roles.id', '=', 'users.role_id')
        ->select('users.id', 'users.last_name', 'users.first_name', 'users.email', 'users.mobile_number', 'roles.name', 'users.status')
        ->get();

        return response()->json($users);
    }
    
    /**
     * Create User 
     * @param Nill
     * @return Array $user
     * @author Shani Singh
     */
    public function create()
    {
        $roles = Role::all();
       
        return view('users.add', ['roles' => $roles]);
    }

    /**
     * Store User
     * @param Request $request
     * @return View Users
     * @author Shani Singh
     */
    public function store(Request $request)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email',
            'mobile_number' => 'required|numeric|digits:8',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user = User::create([
                'last_name'     => $request->last_name,
                'first_name'    => $request->first_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
                'password'      => Hash::make($request->last_name.'@'.$request->mobile_number)
            ]);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success_message','Utilisateur est créé avec succès.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_message', $th->getMessage());
        }
    }

    /**
     * Update Status Of User
     * @param Integer $status
     * @return List Page With Success
     * @author Shani Singh
     */
    public function updateStatus($user_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'user_id'   => $user_id,
            'status'    => $status
        ], [
            'user_id'   =>  'required|exists:users,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('users.index')->with('error_message', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($user_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('users.index')->with('success_message','Statut de l\'utilisateus modifié avec succès!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error_message', $th->getMessage());
        }
    }

    /**
     * Edit User
     * @param Integer $user
     * @return Collection $user
     * @author Shani Singh
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit')->with([
            'roles' => $roles,
            'user'  => $user
        ]);
    }

    /**
     * Update User
     * @param Request $request, User $user
     * @return View Users
     * @author Shani Singh
     */
    public function update(Request $request, User $user)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email,'.$user->id.',id',
            'mobile_number' => 'required|numeric|digits:8',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user_updated = User::whereId($user->id)->update([
                'last_name'     => $request->last_name,
                'first_name'    => $request->first_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
            ]);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success_message','Utilisateur est modifié avec succès.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_message', $th->getMessage());
        }
    }

    public function update_avatar(Request $request){
    	// Handle the user upload of avatar
    	 $user = Auth::user();

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
        		Image::make($avatar)->resize(300, 300)->save( public_path('/admin/img/' . $filename ) );
        		$user = Auth::user();
        		$user->avatar = $filename;
            }
            $user->save();
		return redirect('/profile')->with('success_message', 'Votre photo de profile est mise à jour avec succès!');
    }

    /**
     * Delete User
     * @param User $user
     * @return Index Users
     * @author Shani Singh
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            
            return redirect()->route('users.index')
                ->with('success_message', 'L\'utilisateur a été supprimé avec succès.');
        } catch (Exception $exception) {
            return back()->with('error_message', 'Une erreur inattendue s’est produite lors de la tentative de traitement de votre demande.');
        }
    }


    /**
     * Import Users 
     * @param Null
     * @return View File
     */
    public function email_passwd()
    {
        return view('auth.passwords.email_password');
    }

    public function reset_password_user(Request $request){

        dd($request->all());

        $email = $request->email;

        $result = DB::table('users')
                ->where('email', $email)
                ->count();

        if ($result > 0) {
            // Générer un token unique
            $token = bin2hex(random_bytes(50));
            // Insérer le token dans la base de données avec une expiration (par exemple 1 heure)
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
            $sql = DB::table('password_resets')->insert([
                "email" => $email,
                "token" => $token,
                "expiry" => $expiry
            ]);

            if ($sql === TRUE) {
                // Envoyer l'e-mail de réinitialisation
                $resetLink = "/reset_password_form.php?token=$token";
                $subject = "Réinitialisation de votre mot de passe";
                $message = "Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetLink";
                $headers = "From: ebotcho@anadeb.org";

                if (mail($email, $subject, $message, $headers)) {
                    return redirect()->route('users.index')->with('success_message', 'Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.');
                } else {
                    return redirect()->route('users.index')->with('success_errors', 'Erreur lors de l\'envoi de l\'e-mail.');
                }
            } else {
                return redirect()->route('users.index')->with('success_errors', 'Erreur lors de la génération du token.');
            }
        } else {
            return redirect()->route('users.index')->with('success_errors', 'Aucun utilisateur trouvé avec cette adresse e-mail.');
        }
    }

    /**
     * Import Users 
     * @param Null
     * @return View File
     */
    public function importUsers()
    {
        return view('users.import');
    }

    /**public function uploadUsers(Request $request)
    {
        Excel::import(new UsersImport, $request->file);
        
        return redirect()->route('users.index')->with('success_message', 'User Imported Successfully');
    }*/

    public function uploadUsers(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file'); // Assurez-vous que le nom du champ du formulaire correspond

        $data = Excel::toArray([], $file);

        $firstIteration = true;
        $lignes_excel = [];
        $lignes = [];
        $rowCount = 0;
         // Maintenant, $data contient le contenu du fichier Excel sous forme de tableau
        // Chaque élément du tableau représente une feuille dans le fichier Excel
        foreach ($data as $sheet) {
            foreach ($sheet as $row) {
                if ($firstIteration) {
                    $firstIteration = false;
                    continue; // Passe à la prochaine itération sans exécuter le reste du code
                }
                $lignes_excel['last_name'] = $row['0'];
                $lignes_excel['first_name'] = $row['1'];
                $lignes_excel['email'] = $row['2'];
                $lignes_excel['mobile_number'] = $row['3'];
                $lignes_excel['role_id'] = $row['5'];
                $lignes_excel['status'] = $row['6'];
                $lignes_excel['password'] = Hash::make($row['0'].'@'.$row['3']);
                
                $lignes[] = $lignes_excel;
                
                $lignes_excel = [];
            }
        }
        //dd($lignes);

        for ($i=0; $i < count($lignes) ; $i++) {
            $insertedId = DB::table('users')->insert([
                'last_name' => $lignes[$i]["last_name"],
                'first_name' => $lignes[$i]["first_name"],
                'email' => $lignes[$i]["email"],
                'mobile_number' => $lignes[$i]["mobile_number"],
                'role_id' => $lignes[$i]["role_id"],
                'status' => $lignes[$i]["status"],
                'password' => $lignes[$i]["password"]
            ]);
            if ($insertedId) {
                $rowCount++;
            }
        }
        
        if ($insertedId) {
            return redirect()->route('users.index')->with('success_message', ''.$rowCount.'Utilisateur(s) importé(s) avec succès!');
        }else{
            return redirect()->route('users.index')->with('errors_message', 'Erreur d\'importation du fichier');
        }
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function mise_a_jour(Request $request){

            $token = $request->token;
            $new_password = Hash::make($request->new_password);

            // Vérifier si le token est valide et n'a pas expiré
            $result = DB::table('password_resets')
                    ->select('email')
                    ->where('token', $token)
                    ->where('expiry', '>', NOW())
                    ->get();

            if ($result->count() > 0) {
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Mettre à jour le mot de passe de l'utilisateur
                $user_updated = User::where('email', $email)->update([
                    'password'=> $new_password
                ]);

                if ($conn->query($sql) === TRUE) {
                    // Supprimer le token de réinitialisation
                    DB::table('password_resets')->where('token', $token)->delete();

                    return redirect()->route('login')->with('success_message', 'Votre mot de passe a été réinitialisé avec succès.');
                } else {
                    return redirect()->route('users.index')->with('success_errors', 'Erreur lors de la mise à jour du mot de passe.');
                }
            } else {
                return redirect()->route('users.index')->with('success_message', 'Le lien de réinitialisation est invalide ou a expiré.');
            }
        }

}
