<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcolesController;
use App\Http\Controllers\VillagesController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\RepasController;
use App\Http\Controllers\CantonsController;
use App\Http\Controllers\CommunesController;
use App\Http\Controllers\FinancementsController;
use App\Http\Controllers\PrefecturesController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\VisitesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ContratsController;
use App\Http\Controllers\EntreprisesController;
use App\Http\Controllers\TypeouvragesController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\OuvragesController;
use App\Http\Controllers\SignersController;
use App\Http\Controllers\SuivisController;
use App\Http\Controllers\TempController;
use App\Http\Controllers\MenageController;
use App\Http\Controllers\BeneficiaireController;
use App\Http\Controllers\LocationController;

use App\Http\Controllers\InscritController;
use App\Http\Controllers\SynthesePaiementController;

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\Demande_jourController;

Route::middleware(['public.api'])->group(function () {
    Route::get('public/api/data', 'App\Http\Controllers\PublicController@index')->name('data');
});


//Auth::routes();
Auth::routes(['verify' => true]);

Auth::routes(['register' => false]);

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home_infra', [HomeController::class, 'index_infra'])->name('home_infra');
Route::get('/home_fsb', [HomeController::class, 'index_fsb'])->name('home_fsb');
Route::get('/home_cantine', [HomeController::class, 'index_cantine'])->name('home_cantine');

// Profile Routes
Route::prefix('profile')->name('profile.')->group(function(){
    Route::post('profil', [UserController::class, 'update_avatar'])->name('update_avatar');
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

// Roles
Route::prefix('roles')->name('roles.')->group(function(){
    Route::get('/', [RolesController::class, 'index'])->name('index');
    Route::get('/create', [RolesController::class, 'create'])->name('create');
    Route::post('/store', [RolesController::class, 'store'])->name('store');
    Route::get('/edit/{role}', [RolesController::class, 'edit'])->name('edit');
    Route::put('/update/{role}', [RolesController::class, 'update'])->name('update');
    Route::delete('/destroy/{role}', [RolesController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [RolesController::class, 'fetch']);
});

// Permissions
Route::prefix('permissions')->name('permissions.')->group(function(){
    Route::get('/', [PermissionsController::class, 'index'])->name('index');
    Route::get('/create', [PermissionsController::class, 'create'])->name('create');
    Route::post('/store', [PermissionsController::class, 'store'])->name('store');
    Route::get('/edit/{permission}', [PermissionsController::class, 'edit'])->name('edit');
    Route::post('/update/{permission}', [PermissionsController::class, 'update'])->name('update');
    Route::delete('/destroy/{permission}', [PermissionsController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [PermissionsController::class, 'fetch']);
});

// Users
Route::prefix('users')->name('users.')->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');
    Route::get('fetch', [UserController::class, 'fetch']);

    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');

    Route::get('export/', [UserController::class, 'export'])->name('export');
});


Route::prefix('ecoles')->name('ecoles.')->group(function()
{
    Route::get('ecole_par_region',[EcolesController::class, 'ecole_region']);
    Route::get('ecole_par_region/{region}',[EcolesController::class, 'liste_ecole_region']);
    Route::get('ecole_par_prefecture',[EcolesController::class, 'ecole_prefecture']);
    Route::get('ecole_par_prefecture/{prefecture}',[EcolesController::class, 'liste_ecole_prefecture']);
    Route::get('ecole_par_commune',[EcolesController::class, 'ecole_commune']);
    Route::get('ecole_par_commune/{commune}',[EcolesController::class, 'liste_ecole_commune']);
    Route::get('ecole_par_canton',[EcolesController::class, 'ecole_canton']);
    Route::get('ecole_par_canton/{canton}',[EcolesController::class, 'liste_ecole_canton']);
    Route::get('ecole_par_village',[EcolesController::class, 'ecole_village']);
    Route::get('ecole_par_village/{village}',[EcolesController::class, 'liste_ecole_village']);
    Route::get('/', [EcolesController::class, 'index'])->name('index');
    Route::get('/create', [EcolesController::class, 'create'])->name('create');
    Route::get('/show/{ecole}',[EcolesController::class, 'show'])->name('show');
    Route::get('/{ecole}/edit',[EcolesController::class, 'edit'])->name('edit');
    Route::post('/', [EcolesController::class, 'store'])->name('store');
    Route::put('ecole/', [EcolesController::class, 'update'])->name('update');
    Route::delete('/{ecole}',[EcolesController::class, 'destroy']);
    Route::get('fetch', [EcolesController::class, 'fetch']);
    Route::get('/update/status/{id}/{status}', [EcolesController::class, 'updateStatus'])->name('status');
    Route::get('get-options/{ecole}',[EcolesController::class, 'get_options']);
    Route::get('/{ecole}',[EcolesController::class, 'ecole']);
    Route::get('get-options/{village}',[EcolesController::class, 'get_options']);
    // Route::get('canton/{canton}',[CantonsController::class, 'ecoleCanton']);
});


Route::prefix('villages')->name('villages.')->group(function(){
    Route::get('/', [VillagesController::class, 'index'])->name('index');
    Route::get('/create', [VillagesController::class, 'create'])->name('create');
    Route::get('/show/{village}',[VillagesController::class, 'show'])->name('show');
    Route::get('/{village}/edit',[VillagesController::class, 'edit'])->name('edit');
    Route::post('/', [VillagesController::class, 'store'])->name('store');
    Route::put('village/', [VillagesController::class, 'update'])->name('update');
    Route::delete('/{village}',[VillagesController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [VillagesController::class, 'fetch']);
    Route::get('/{village}',[VillagesController::class, 'village']);
    Route::get('get-options/{village}',[VillagesController::class, 'get_options']);
    Route::get('get-option/{village}',[VillagesController::class, 'get_option_comm']);
});

Route::prefix('classes')->name('classes.')->group(function(){
    Route::get('/', [ClassesController::class, 'index'])->name('index');
    Route::get('/zero', [ClassesController::class, 'index_zero'])->name('index_zero');
    Route::get('/create', [ClassesController::class, 'create'])->name('create');
    Route::get('/show/{classe}',[ClassesController::class, 'show'])->name('show');
    Route::get('/{classe}/edit',[ClassesController::class, 'edit'])->name('edit');
    Route::post('/', [ClassesController::class, 'store'])->name('store');
    Route::put('classe/', [ClassesController::class, 'update'])->name('update');
    Route::delete('/destroy/{classe}',[ClassesController::class, 'destroy'])->name('destroy');
    Route::get('/fetch/zero', [ClassesController::class, 'ecoleazero']);
    Route::get('fetch', [ClassesController::class, 'fetch']);
    Route::get('/{classe}',[ClassesController::class, 'classe']);
    Route::get('get-options/{classe}',[ClassesController::class, 'get_options']);
    Route::get('ecole/{classe}',[ClassesController::class, 'classeEcole']);
});

Route::prefix('inscrits')->name('inscrits.')->group(function(){
    Route::get('/', [InscritController::class, 'index'])->name('index');
    Route::get('/zero', [InscritController::class, 'index_zero'])->name('index_zero');
    Route::get('/create', [InscritController::class, 'create'])->name('create');
    Route::get('/show/{inscrit}',[InscritController::class, 'show'])->name('show');
    Route::get('/{inscrit}/edit',[InscritController::class, 'edit'])->name('edit');
    Route::post('/', [InscritController::class, 'store'])->name('store');
    Route::put('inscrit/', [InscritController::class, 'update'])->name('update');
    Route::delete('/destroy/{inscrit}',[InscritController::class, 'destroy'])->name('destroy');
    Route::get('/fetch/zero', [InscritController::class, 'ecoleazero']);
    Route::get('fetch', [InscritController::class, 'fetch']);
    Route::get('/{inscrit}',[InscritController::class, 'classe']);
    Route::get('/update/status/{id}/{status}', [InscritController::class, 'updateStatus'])->name('status');
    Route::get('get-options/{inscrit}',[InscritController::class, 'get_options']);
    Route::get('inscrit/{inscrit}',[InscritController::class, 'classeEcole']);
});

Route::prefix('menus')->name('menus.')->group(function(){
    Route::get('/', [MenusController::class, 'index'])->name('index');
    Route::get('/create', [MenusController::class, 'create'])->name('create');
    Route::get('/show/{menu}',[MenusController::class, 'show'])->name('show');
    Route::get('/{menu}/edit',[MenusController::class, 'edit'])->name('edit');
    Route::post('/', [MenusController::class, 'store'])->name('store');
    Route::put('menu/', [MenusController::class, 'update'])->name('update');
    Route::get('/update/status/{id}/{statut}', [MenusController::class, 'updateStatut'])->name('statut');
    Route::delete('/{menu}',[MenusController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [MenusController::class, 'fetch']);
});
Route::prefix('syntheses')->name('syntheses.')->group(function(){
    Route::get('synthese/ecole', [RepasController::class, 'synthese_ecole'])->name('synthese_ecole');
    Route::get('synthese/canton', [RepasController::class, 'synthese_canton'])->name('synthese_canton');
    Route::get('synthese/commune', [RepasController::class, 'synthese_commune'])->name('synthese_commune');
    Route::get('synthese/prefecture', [RepasController::class, 'synthese_prefecture'])->name('synthese_prefecture');
    Route::get('synthese/region', [RepasController::class, 'synthese_region'])->name('synthese_region');
    Route::get('synthese/comptabilite', [RepasController::class, 'synthese_comptabilite'])->name('synthese_comptabilite');
});

Route::prefix('repas')->name('repas.')->group(function(){
    Route::get('/', [RepasController::class, 'index'])->name('index');
    Route::get('test/{repas}', [RepasController::class, 'test']);
    Route::get('/create', [RepasController::class, 'create'])->name('create');
    Route::get('/show/{repas}',[RepasController::class, 'show'])->name('show');
    Route::get('/{repas}/edit',[RepasController::class, 'edit'])->name('edit');
    Route::post('/', [RepasController::class, 'store'])->name('store');
    Route::put('/{repas}', [RepasController::class, 'update']);
    Route::delete('/{repas}',[RepasController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [RepasController::class, 'fetch']);
    Route::get('synthese/arf', [RepasController::class, 'synthese_arf'])->name('synthese_arf');
    Route::get('arf', [RepasController::class, 'arf'])->name('arf');
    Route::get('compta', [RepasController::class, 'par_compta'])->name('compta');
    Route::get('ecole', [RepasController::class, 'par_ecole'])->name('par_ecole');
    Route::get('canton', [RepasController::class, 'par_canton'])->name('canton');
    Route::get('commune', [RepasController::class, 'par_commune'])->name('commune');
    Route::get('prefecture', [RepasController::class, 'par_prefecture'])->name('prefecture');
    Route::get('region', [RepasController::class, 'par_region'])->name('region');
    Route::post('import', [RepasController::class, 'import'])->name('import');
    
    Route::get('/format_charger', [RepasController::class, 'format_charger'])->name('format_charger');
    Route::post('format_telecharger', [RepasController::class, 'format_telecharger'])->name('format_telecharger');

    /** Les statistiques */
    Route::get('par_sexe', [RepasController::class, 'char_parsexe'])->name('par_sexe');
    Route::get('par_fin', [RepasController::class, 'char_parfinancement'])->name('par_fin');
    Route::get('par_fin_date', [RepasController::class, 'char_parfinancement_date'])->name('par_fin_date');
    Route::get('char_parregion', [RepasController::class, 'char_parregion'])->name('char_parregion');
});

Route::prefix('cantons')->name('cantons.')->group(function(){
    Route::get('/', [CantonsController::class, 'index'])->name('index');
    Route::get('/create', [CantonsController::class, 'create'])->name('create');
    Route::get('/show/{canton}',[CantonsController::class, 'show'])->name('show');
    Route::get('/{canton}/edit',[CantonsController::class, 'edit'])->name('edit');
    Route::post('/', [CantonsController::class, 'store'])->name('store');
    Route::put('canton/', [CantonsController::class, 'update'])->name('update');
    Route::delete('/{canton}',[CantonsController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [CantonsController::class, 'fetch']);
    //Route::get('/{canton}',[CantonsController::class, 'canton']);
    Route::get('get-options/{canton}',[CantonsController::class, 'get_options']);
    Route::get('canton/{canton}',[CantonsController::class, 'cantonRegions']);
    Route::get('/{canton}',[CantonsController::class, 'cantonCommune']);
});

Route::prefix('communes')->name('communes.')->group(function(){
    Route::get('/', [CommunesController::class, 'index'])->name('index');
    Route::get('/create', [CommunesController::class, 'create'])->name('create');
    Route::get('/show/{commune}',[CommunesController::class, 'show'])->name('show');
    Route::get('/{commune}/edit',[CommunesController::class, 'edit'])->name('edit');
    Route::post('/', [CommunesController::class, 'store'])->name('store');
    Route::put('commune/', [CommunesController::class, 'update'])->name('update');
    Route::delete('{commune}',[CommunesController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [CommunesController::class, 'fetch']);
    //Route::get('/{commune}',[CommunesController::class, 'commune']);
    Route::get('get-options/{commune}',[CommunesController::class, 'get_options']);

    Route::get('/{region}',[CommunesController::class, 'communeRegion']);
});

Route::prefix('financements')->name('financements.')->group(function(){
    Route::get('/', [FinancementsController::class, 'index'])->name('index');
    Route::get('/create', [FinancementsController::class, 'create'])->name('create');
    Route::get('/show/{financement}',[FinancementsController::class, 'show'])->name('show');
    Route::get('/{financement}/edit',[FinancementsController::class, 'edit'])->name('edit');
    Route::post('/', [FinancementsController::class, 'store'])->name('store');
    Route::put('financement/', [FinancementsController::class, 'update'])->name('update');
    Route::delete('/{financement}',[FinancementsController::class, 'destroy']);
    Route::get('fetch', [FinancementsController::class, 'fetch']);
});

Route::prefix('prefectures')->name('prefectures.')->group(function(){
    Route::get('/', [PrefecturesController::class, 'index'])->name('index');
    Route::get('/create', [PrefecturesController::class, 'create'])->name('create');
    Route::get('/show/{prefecture}',[PrefecturesController::class, 'show'])->name('show');
    Route::get('/{prefecture}/edit',[PrefecturesController::class, 'edit'])->name('edit');
    Route::post('/', [PrefecturesController::class, 'store'])->name('store');
    Route::put('prefecture/', [PrefecturesController::class, 'update'])->name('update');
    Route::delete('/{prefecture}',[PrefecturesController::class, 'destroy'])->name('destroy');
    Route::get('/fetch', [PrefecturesController::class, 'fetch']);
    Route::get('/{prefecture}',[PrefecturesController::class, 'prefecture']);
    
});

Route::prefix('regions')->name('regions.')->group(function(){
    Route::get('/', [RegionsController::class, 'index'])->name('index');
    Route::get('/create', [RegionsController::class, 'create'])->name('create');
    Route::get('/show/{region}',[RegionsController::class, 'show'])->name('show');
    Route::get('/{region}/edit',[RegionsController::class, 'edit'])->name('edit');
    Route::post('/', [RegionsController::class, 'store'])->name('store');
    Route::put('region/', [RegionsController::class, 'update'])->name('update');
    Route::delete('/{region}',[RegionsController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [RegionsController::class, 'fetch']);
    Route::get('/autocomplete_phoneMenage', [RegionsController::class, 'autocomplete_phoneMenage']);
    Route::get('/autocomplete_ecl', [RegionsController::class, 'autorecherche_ecl']);
    Route::get('/autocomplete_vill', [RegionsController::class, 'autorecherche_vill']);
    Route::get('/autocomplete_cant', [RegionsController::class, 'autorecherche_cant']);
    Route::get('/autocomplete_comm', [RegionsController::class, 'autorecherche_comm']);
    Route::get('/autocomplete_pref', [RegionsController::class, 'autorecherche_pref']);
    Route::get('/autocomplete_reg', [RegionsController::class, 'autorecherche_reg']);
    Route::get('/autocomplete_fin', [RegionsController::class, 'autorecherche_fin']);
});

Route::prefix('visites')->name('visites.')->group(function(){
    Route::get('/', [VisitesController::class, 'index'])->name('index');
    Route::get('/create', [VisitesController::class, 'create'])->name('create');
    Route::get('/show/{visite}',[VisitesController::class, 'show'])->name('show');
    Route::get('/{visite}/edit',[VisitesController::class, 'edit'])->name('edit');
    Route::post('/', [VisitesController::class, 'store'])->name('store');
    Route::put('visite/', [VisitesController::class, 'update'])->name('update');
    Route::delete('/{visite}',[VisitesController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [VisitesController::class, 'fetch']);
});

Route::prefix('contrats')->name('contrats.')->group(function()
{
    Route::get('/', [ContratsController::class, 'index'])->name('index');
    Route::get('/create', [ContratsController::class, 'create'])->name('create');
    Route::get('/email_entreprise/{contrat}', [ContratsController::class, 'email_entreprise'])->name('email_entreprise');
    Route::get('/show/{signer}',[ContratsController::class, 'show'])->name('show');
    Route::get('/{contrat}/edit',[ContratsController::class, 'edit'])->name('edit');
    Route::post('/', [ContratsController::class, 'store'])->name('store');
    Route::put('/{contrat}', [ContratsController::class, 'update'])->name('update');
    Route::delete('/{contrat}',[ContratsController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [ContratsController::class, 'fetch']);
});

Route::prefix('entreprises')->name('entreprises.')->group(function()
{
    Route::get('/', [EntreprisesController::class, 'index'])->name('index');
    Route::get('/create', [EntreprisesController::class, 'create'])->name('create');
    Route::get('/show/{entreprise}',[EntreprisesController::class, 'show'])->name('show');
    Route::get('/{entreprise}/edit',[EntreprisesController::class, 'edit'])->name('edit');
    Route::post('/', [EntreprisesController::class, 'store'])->name('store');
    Route::put('entreprise/', [EntreprisesController::class, 'update'])->name('update');
    Route::delete('/{entreprise}',[EntreprisesController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [EntreprisesController::class, 'fetch']);

    Route::post('import', [EntreprisesController::class, 'import'])->name('import');
});

Route::prefix('typeouvrages')->name('typeouvrages.')->group(function()
{
    Route::get('/', [TypeouvragesController::class, 'index'])->name('index');
    Route::get('/create', [TypeouvragesController::class, 'create'])->name('create');
    Route::get('/show/{typeouvrage}',[TypeouvragesController::class, 'show'])->name('show');
    Route::get('/{typeouvrage}/edit',[TypeouvragesController::class, 'edit'])->name('edit');
    Route::post('/', [TypeouvragesController::class, 'store'])->name('store');
    Route::put('/{typeouvrage}', [TypeouvragesController::class, 'update'])->name('update');
    Route::delete('/{typeouvrage}',[TypeouvragesController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [TypeouvragesController::class, 'fetch']);
    
});

Route::prefix('sites')->name('sites.')->group(function()
{
    Route::get('/', [SiteController::class, 'index'])->name('index');
    Route::get('/create', [SiteController::class, 'create'])->name('create');
    Route::get('/show/{site}',[SiteController::class, 'show'])->name('show');
    Route::get('/detail/{site}',[SiteController::class, 'detail'])->name('show');
    Route::get('/site_commune/{site}',[SiteController::class, 'site_commune']);
    Route::get('/{site}/edit',[SiteController::class, 'edit'])->name('edit');
    Route::post('/', [SiteController::class, 'store'])->name('store');
    Route::get('verification', [SiteController::class, 'verification']);
    Route::put('site/', [SiteController::class, 'update'])->name('update');
    Route::delete('/{site}',[SiteController::class, 'destroy']);
    Route::get('fetch', [SiteController::class, 'fetch']);
    Route::get('ouvrage_site/{site}',[SiteController::class, 'ouvrage_site']);
    Route::get('ouvcontrat_sign/{commune}',[SiteController::class, 'ouvcontrat_sign']);

    Route::post('import', [SiteController::class, 'import'])->name('import');


    //Routes pour la modale d'exportation du format csv
    Route::get('telecharger', [SiteController::class, 'telecharger']);
    Route::get('format_csv', [SiteController::class, 'format_csv'])->name('format_csv');
});

Route::prefix('ouvrages')->name('ouvrages.')->group(function()
{
    Route::get('/', [OuvragesController::class, 'index'])->name('index');
    Route::get('/create', [OuvragesController::class, 'create'])->name('create');
    Route::get('/show/{ouvrage}',[OuvragesController::class, 'show'])->name('show');
    Route::get('/{ouvrage}/edit',[OuvragesController::class, 'edit'])->name('edit');
    Route::post('/', [OuvragesController::class, 'store'])->name('store');
    Route::put('ouvrage/', [OuvragesController::class, 'update'])->name('update');
    Route::delete('/{ouvrage}',[OuvragesController::class, 'destroy'])->name('destroy');
    Route::get('get-sign/{commune}',[OuvragesController::class, 'get_sign']);
    Route::get('fetch', [OuvragesController::class, 'fetch']);
    Route::get('statistiques', [OuvragesController::class, 'statistique_ouvrages']);
    Route::get('statut/{statu}', [OuvragesController::class, 'index_statut'])->name('statut');
    Route::post('import', [OuvragesController::class, 'import'])->name('import');

    Route::get('/update/status/{id}/{status}', [OuvragesController::class, 'updateStatus'])->name('status');

    Route::get('telecharger', [OuvragesController::class, 'telecharger']);
    Route::get('format_csv', [OuvragesController::class, 'format_csv'])->name('format_csv');
});

Route::prefix('signers')->name('signers.')->group(function()
{
    Route::get('/', [SignersController::class, 'index'])->name('index');
    Route::get('/create', [SignersController::class, 'create'])->name('create');
    Route::get('/show/{signer}',[SignersController::class, 'show'])->name('show');
    Route::get('/{signer}/edit',[SignersController::class, 'edit'])->name('edit');
    Route::post('/', [SignersController::class, 'store'])->name('store');
    Route::put('signer/', [SignersController::class, 'update'])->name('update');
    Route::delete('/{signer}',[SignersController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [SignersController::class, 'fetch']);
});

Route::prefix('suivis')->name('suivis.')->group(function()
{
    Route::get('/', [SuivisController::class, 'index'])->name('index');
    Route::get('/create', [SuivisController::class, 'create'])->name('create');
    Route::get('galerie/create', [SuivisController::class, 'create_photos'])->name('galerie.create');
    Route::get('/show/{suivi}',[SuivisController::class, 'show'])->name('show');
    Route::get('/{suivi}/edit',[SuivisController::class, 'edit'])->name('edit');
    Route::post('/', [SuivisController::class, 'store'])->name('store');
    Route::post('galerie/', [SuivisController::class, 'store_photos'])->name('galerie.store');
    Route::put('suivi/', [SuivisController::class, 'update'])->name('update');
    Route::delete('/{suivi}',[SuivisController::class, 'destroy'])->name('destroy');
    Route::delete('/galerie/{galerie}',[SuivisController::class, 'destroy_galerie'])->name('destroy_galerie');
    Route::get('fetch', [SuivisController::class, 'fetch']);
    Route::get('galerie', [SuivisController::class, 'galerie'])->name('galerie');
    Route::get('galerie/type', [SuivisController::class, 'type'])->name('galerie.type');
    Route::get('galerie/post', [SuivisController::class, 'galeriePost'])->name('galerie.post');
    Route::get('get-options/{village}',[SuivisController::class, 'get_options']);
    Route::get('galerie/get_option/{canton}',[SuivisController::class, 'get_option']);
});

Route::prefix('temps')->name('temps.')->group(function()
{
    Route::get('/', [TempController::class, 'index'])->name('index');
    Route::post('charger', [TempController::class, 'charger'])->name('charger');
    Route::post('import', [TempController::class, 'import'])->name('import');
});

Route::prefix('locations')->name('locations.')->group(function()
{
    Route::get('/cartographie_des_points', [LocationController::class, 'cartographie'])->name('cartographie');
    Route::get('/', [LocationController::class, 'index'])->name('index');
    Route::get('/create', [LocationController::class, 'create'])->name('create');
    Route::post('/save-coordinates', [LocationController::class, 'store']);
    Route::delete('/{location}',[LocationController::class, 'destroy'])->name('destroy');
    Route::get('fetch', [LocationController::class, 'fetch']);
    Route::get('fetch_map', [LocationController::class, 'fetch_map']);
    
    Route::get('/galerie/{ouvrage}', [SuivisController::class, 'show_photos'])->name('show_photos');
});


Route::prefix('menages')->name('menages.')->group(function()
{
    Route::get('/', [MenageController::class, 'index'])->name('index');
    Route::get('fetch', [MenageController::class, 'fetch']);
    Route::get('membres/{id_menage}', [MenageController::class, 'liste_membres']);
    Route::get('liste/membre', [MenageController::class, 'liste_membre']);
});

Route::prefix('beneficiaires')->name('beneficiaires.')->group(function()
{
    Route::get('/', [BeneficiaireController::class, 'index'])->name('index');
    Route::get('/show/{beneficiaire}',[BeneficiaireController::class, 'show'])->name('show');
    Route::get('/paiement/{paiement}',[BeneficiaireController::class, 'paiements'])->name('paiements');
    Route::get('fetch', [BeneficiaireController::class, 'fetch']);
    Route::get('etat_paiements', [BeneficiaireController::class, 'etat_paiements'])->name('etat_paiements');
    Route::get('fetch_etat_paiements', [BeneficiaireController::class, 'fetch_etat_paiements']);
    Route::get('getTotalFemmes', [BeneficiaireController::class, 'getTotalFemmes']);
    Route::get('countBeneficiairesAges60Plus', [BeneficiaireController::class, 'countBeneficiairesAges60Plus']);
    Route::get('getTotalFemmesEtHommes', [BeneficiaireController::class, 'getTotalFemmesEtHommes']);
    Route::get('auMoinsUnAge', [BeneficiaireController::class, 'auMoinsUnAge']);
    Route::get('cible_par_region', [BeneficiaireController::class, 'cible_par_region']);
    Route::get('cible_par_six_tranche', [BeneficiaireController::class, 'cible_par_six_tranche']);
    Route::get('cible_par_financement', [BeneficiaireController::class, 'cible_par_financement']);
    Route::get('cible_par_financement_boucles', [BeneficiaireController::class, 'cible_par_financement_boucles']);
});

Route::prefix('fsb_syntheses')->name('fsb_syntheses.')->group(function()
{
    Route::get('par_village', [SynthesePaiementController::class, 'par_village'])->name('par_village');
    Route::get('fetch', [SynthesePaiementController::class, 'fetch'])->name('fetch');
    Route::get('par_beneficiaire', [SynthesePaiementController::class, 'par_beneficiaire'])->name('par_beneficiaire');
    Route::get('fetch_beneficiaire', [SynthesePaiementController::class, 'fetch_beneficiaire'])->name('fetch_beneficiaire');
});


Route::prefix('demandes')->name('demandes.')->group(function()
{
    Route::get('/', [DemandeController::class, 'index'])->name('index');
    Route::get('fetch', [DemandeController::class, 'fetch']);
    Route::get('/approvals/{id}/{status}', [DemandeController::class, 'approve'])->name('status');
    Route::get('/create', [DemandeController::class, 'create'])->name('create');
    Route::post('/', [DemandeController::class, 'store'])->name('store');
    Route::get('/detail/{id}/{iid}',[DemandeController::class, 'detail'])->name('detail');
});

Route::prefix('demandejours')->name('demandejours.')->group(function()
{
    Route::get('/', [Demande_jourController::class, 'index'])->name('index');
    Route::get('fetch', [Demande_jourController::class, 'fetch']);
    Route::get('/approvals/{id}/{status}/{date_fin}', [Demande_jourController::class, 'approve'])->name('status');
    Route::get('/create', [Demande_jourController::class, 'create'])->name('create');
    Route::post('/', [Demande_jourController::class, 'store'])->name('store');
    Route::get('/detail/{id}',[Demande_jourController::class, 'detail'])->name('detail');
});