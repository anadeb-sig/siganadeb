<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Beneficiaire;
use App\Models\Financement;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function index_infra()
    {
        
        $projets = DB::table('projets')
                ->whereIn('name', ['INFRASTRUCTURE/COSO', 'INFRASTRUCTURE/CLASSIQUE'])
                ->get();

        $Financements = Financement::all();

        return view('home_infra', compact('projets','Financements'));
    }

    public function index_fsb()
    {
        return view('home_fsb');
    }

    public function index_cantine()
    {
        return view('home_cantine');
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'email'         => 'required|unique:users,email,'.auth()->user()->id.',id',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:8',
        ]);

        try {
            DB::beginTransaction();
            
            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success_message', 'Profile est modifié avec succès.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error_message', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            
            #Commit Transaction
            DB::commit();

            return redirect('/logout');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
