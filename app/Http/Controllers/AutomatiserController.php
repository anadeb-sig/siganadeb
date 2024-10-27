<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Beneficiaire;
use DB;
use App\Models\Contrat;
use App\Models\Site;
use App\Models\Demande;

class AutomatiserController extends Controller
{
    
    // Met à jjour les pending account dans la table de paiements
    public function updatePaiementsStatus(){
        ini_set('max_execution_time', 0); // infini

        DB::update("
            UPDATE paiements p SET p.`status`='Success' WHERE p.ref in (SELECT t.ref FROM temp_references t)
        ");
    }


    //Génération des états de paiement puis inserrer dans la table 'etatpaiements'
    public function insertEtatPaiements(){

        DB::table('etatpaiements')->truncate();

        ini_set('max_execution_time', 0); // 5 minutes

        DB::insert("
            INSERT INTO etatpaiements (id,telephone,type_card,mobile_money,card_number,nom,prenom,sexe,tm,mie,financement,village_id, nbr, SommeSucces, nombre_succes,SommeTM,SommeMIE, SommePending, nombre_pending, SommeCancel, nombre_Cancel, nombre_Fail)
                SELECT 
                    b.id,
                    b.telephone,
                        p.type_card,
                        p.mobile_money,
                    b.card_number,
                    b.nom,
                    b.prenom,
                    b.sexe,
                    b.tm,
                    b.mie,
                    b.financement,
                    b.village_id,	  
                    COUNT(p.telephone) AS nbr,
                    SUM(CASE WHEN p.status = 'Success' THEN p.montant ELSE 0 END) AS SommeSucces,
                    SUM(CASE WHEN p.status = 'Success' THEN 1 ELSE 0 END) AS nombre_succes,
                    SUM(CASE 
                        WHEN p.status = 'Success' AND p.ref NOT IN (SELECT tem.ref FROM temp_reference_944 tem)
                        AND p.montant BETWEEN 0 AND 65000
                        THEN p.montant
                        ELSE 0
                    END) AS SommeTM,
                SUM(CASE 
                        WHEN p.status = 'Success' AND p.ref IN (SELECT tem.ref FROM temp_reference_944 tem) 
                        THEN p.montant
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN p.status = 'Success' AND p.montant BETWEEN 70000 and 110000
                        THEN p.montant 
                        ELSE 0 
                    END) AS SommeMIE,
                SUM(CASE WHEN p.status = 'PendingAccount' THEN p.montant ELSE 0 END) AS SommePending,
                SUM(CASE WHEN p.status = 'PendingAccount' THEN 1 ELSE 0 END) AS nombre_pending,
                SUM(CASE WHEN p.status = 'Canceled' THEN p.montant ELSE 0 END) AS SommeCancel,
                SUM(CASE WHEN p.status = 'Canceled' THEN 1 ELSE 0 END) AS nombre_Cancel,
                SUM(CASE WHEN p.status = 'Fail' THEN 1 ELSE 0 END) AS nombre_Fail
                FROM paiements p
                INNER JOIN beneficiaires b ON b.id = p.id
                GROUP BY b.id, b.telephone,p.type_card,p.mobile_money,b.card_number, b.nom, b.prenom, b.sexe, b.tm, b.mie, b.financement, b.village_id;
        ");
    }

    //Génération des paiements par tranche puis insertion dans la table 'ptf_villages
    function insertDataPtf_villages(){

        DB::table('ptf_villages')->truncate();

        ini_set('max_execution_time', 0); // infini

        DB::insert("
            INSERT INTO ptf_villages (reg_fa, pref_fa, comm_fa, cant_fa, vill_fa, financement, CardType, CardNum, Gender, TransferNumber, TransferCarrier, montantR, frais, AllocationPayNumber)
            WITH RECURSIVE seq AS (
                SELECT 1 AS n
                UNION ALL
                SELECT n + 1
                FROM seq
                WHERE n < 6
            ),
            CTE AS (
                SELECT 
                    r.nom_reg,
                    p.nom_pref,
                    cc.nom_comm,
                    c.nom_cant,
                    v.nom_vill,
                    e.financement,
                    e.type_card,
                    e.card_number,
                    e.sexe,
                    e.telephone,
                    e.mobile_money,
                    20000 AS montant,
                    (e.SommeTM - 150000) / 6 AS frais,
                    ROW_NUMBER() OVER (PARTITION BY e.telephone ORDER BY e.SommeTM) AS ligne_numero,
                    seq.n AS seq_num
                FROM etatpaiements e
                JOIN villages v ON e.village_id = v.id
                JOIN cantons c ON v.canton_id = c.id
                JOIN communes cc ON c.commune_id = cc.id
                JOIN prefectures p ON cc.prefecture_id = p.id
                JOIN regions r ON p.region_id = r.id
                JOIN seq ON e.SommeTM BETWEEN 150000 AND 155000
                WHERE seq.n <= 6
                
                UNION ALL

                SELECT 
                    r.nom_reg,
                    p.nom_pref,
                    cc.nom_comm,
                    c.nom_cant,
                    v.nom_vill,
                    e.financement,
                    e.type_card,
                    e.card_number,
                    e.sexe,
                    e.telephone,
                    e.mobile_money,
                    20000 AS montant,
                    (e.SommeTM - 120000) / 6 AS frais,
                    ROW_NUMBER() OVER (PARTITION BY e.telephone ORDER BY e.SommeTM) AS ligne_numero,
                    seq.n AS seq_num
                FROM etatpaiements e
                JOIN villages v ON e.village_id = v.id
                JOIN cantons c ON v.canton_id = c.id
                JOIN communes cc ON c.commune_id = cc.id
                JOIN prefectures p ON cc.prefecture_id = p.id
                JOIN regions r ON p.region_id = r.id
                JOIN seq ON e.SommeTM BETWEEN 120000 AND 125000
                WHERE seq.n <= 6
                
                UNION ALL

                SELECT 
                    r.nom_reg,
                    p.nom_pref,
                    cc.nom_comm,
                    c.nom_cant,
                    v.nom_vill,
                    e.financement,
                    e.type_card,
                    e.card_number,
                    e.sexe,
                    e.telephone,
                    e.mobile_money,
                    15000 AS montant,
                    (e.SommeTM - 90000) / 6 AS frais,
                    ROW_NUMBER() OVER (PARTITION BY e.telephone ORDER BY e.SommeTM) AS ligne_numero,
                    seq.n AS seq_num
                FROM etatpaiements e
                JOIN villages v ON e.village_id = v.id
                JOIN cantons c ON v.canton_id = c.id
                JOIN communes cc ON c.commune_id = cc.id
                JOIN prefectures p ON cc.prefecture_id = p.id
                JOIN regions r ON p.region_id = r.id
                JOIN seq ON e.SommeTM BETWEEN 90000 AND 95000
                WHERE seq.n <= 6
                
                UNION ALL
                
                SELECT 
                    r.nom_reg,
                    p.nom_pref,
                    cc.nom_comm,
                    c.nom_cant,
                    v.nom_vill,
                    e.financement,
                    e.type_card,
                    e.card_number,
                    e.sexe,
                    e.telephone,
                    e.mobile_money,
                    15000 AS montant,
                    (e.SommeTM - 75000) / 5 AS frais,
                    ROW_NUMBER() OVER (PARTITION BY e.telephone ORDER BY e.SommeTM) AS ligne_numero,
                    seq.n AS seq_num
                FROM etatpaiements e
                JOIN villages v ON e.village_id = v.id
                JOIN cantons c ON v.canton_id = c.id
                JOIN communes cc ON c.commune_id = cc.id
                JOIN prefectures p ON cc.prefecture_id = p.id
                JOIN regions r ON p.region_id = r.id
                JOIN seq ON e.SommeTM BETWEEN 75000 AND 80000
                WHERE seq.n <= 5
                
                UNION ALL
                
                SELECT 
                    r.nom_reg,
                    p.nom_pref,
                    cc.nom_comm,
                    c.nom_cant,
                    v.nom_vill,
                    e.financement,
                    e.type_card,
                    e.card_number,
                    e.sexe,
                    e.telephone,
                    e.mobile_money,
                    15000 AS montant,
                    (e.SommeTM - 60000) / 4 AS frais,
                    ROW_NUMBER() OVER (PARTITION BY e.telephone ORDER BY e.SommeTM) AS ligne_numero,
                    seq.n AS seq_num
                FROM etatpaiements e
                JOIN villages v ON e.village_id = v.id
                JOIN cantons c ON v.canton_id = c.id
                JOIN communes cc ON c.commune_id = cc.id
                JOIN prefectures p ON cc.prefecture_id = p.id
                JOIN regions r ON p.region_id = r.id
                JOIN seq ON e.SommeTM BETWEEN 60000 AND 65000
                WHERE seq.n <= 4
                
                UNION ALL
                
                SELECT 
                    r.nom_reg,
                    p.nom_pref,
                    cc.nom_comm,
                    c.nom_cant,
                    v.nom_vill,
                    e.financement,
                    e.type_card,
                    e.card_number,
                    e.sexe,
                    e.telephone,
                    e.mobile_money,
                    15000 AS montant,
                    (e.SommeTM - 45000) / 3 AS frais,
                    ROW_NUMBER() OVER (PARTITION BY e.telephone ORDER BY e.SommeTM) AS ligne_numero,
                    seq.n AS seq_num
                FROM etatpaiements e
                JOIN villages v ON e.village_id = v.id
                JOIN cantons c ON v.canton_id = c.id
                JOIN communes cc ON c.commune_id = cc.id
                JOIN prefectures p ON cc.prefecture_id = p.id
                JOIN regions r ON p.region_id = r.id
                JOIN seq ON e.SommeTM BETWEEN 45000 AND 50000
                WHERE seq.n <= 3
                
                UNION ALL
                
                SELECT 
                    r.nom_reg,
                    p.nom_pref,
                    cc.nom_comm,
                    c.nom_cant,
                    v.nom_vill,
                    e.financement,
                    e.type_card,
                    e.card_number,
                    e.sexe,
                    e.telephone,
                    e.mobile_money,
                    15000 AS montant,
                    (e.SommeTM - 30000) / 2 AS frais,
                    ROW_NUMBER() OVER (PARTITION BY e.telephone ORDER BY e.SommeTM) AS ligne_numero,
                    seq.n AS seq_num
                FROM etatpaiements e
                JOIN villages v ON e.village_id = v.id
                JOIN cantons c ON v.canton_id = c.id
                JOIN communes cc ON c.commune_id = cc.id
                JOIN prefectures p ON cc.prefecture_id = p.id
                JOIN regions r ON p.region_id = r.id
                JOIN seq ON e.SommeTM BETWEEN 30000 AND 35000
                WHERE seq.n <= 2
            )
            SELECT 
                nom_reg,
                nom_pref,
                nom_comm,
                nom_cant,
                nom_vill,
                financement,
                type_card,
                card_number,
                sexe,
                telephone,
                mobile_money,
                montant,
                frais,
                ligne_numero
            FROM CTE
            ORDER BY ligne_numero
        ");
    }


    //Génération des paiements par village puis insertion dans la table 'etat_ptf_village'
    public function outil_ptf_village(){
        
        DB::table('etat_ptf_village')->truncate();

        ini_set('max_execution_time', 0); // 5 minutes

        $date1 = Carbon::parse('2022-09-30');
        $date2 = Carbon::parse('2022-12-31');
        $date3 = Carbon::parse('2023-03-31');
        $date4 = Carbon::parse('2023-06-31');

        //dd($date1->format('Y-m-d'));

        $payements = DB::table('ptf_villages')
                ->select(DB::raw('reg_fa, pref_fa, comm_fa, cant_fa, vill_fa,financement, MAX(AllocationPayNumber) as number'))
                ->groupBy('reg_fa', 'pref_fa', 'comm_fa', 'cant_fa', 'vill_fa', 'financement')
                ->get();

        foreach ($payements as $payement) {
            $lignes_excel = [];

            $lignes_excel['reg'] = $payement->reg_fa;
            $lignes_excel['pref'] = $payement->pref_fa;            
            $lignes_excel['comm'] = $payement->comm_fa;
            $lignes_excel['cant'] = $payement->cant_fa;
            $lignes_excel['vill'] = $payement->vill_fa;
            $lignes_excel['financement'] = $payement->financement;

            for ($i=1; $i <= $payement->number; $i++) {                
                $pay = DB::table('ptf_villages')
                    ->select(DB::raw('
                        COUNT(TransferNumber) as "benef", 
                        COUNT(CASE WHEN Gender = "F" THEN "F" ELSE NULL END) as "Femmes",
                        COUNT(CASE WHEN Gender = "H" THEN "H" ELSE NULL END) as "Hommes",
                        SUM(montantR) as "montant",
                        SUM(frais) as "frais",
                        SUM(CASE  WHEN TransferCarrier = "FLOOZ"  THEN frais ELSE NULL END) as "fraisFlooz",
                        SUM(CASE  WHEN TransferCarrier = "Tmoney"  THEN frais ELSE NULL END) as "fraisTmoney",                      
                        COUNT(CASE WHEN TransferCarrier = "FLOOZ" THEN "FLOOZ" ELSE NULL END) as "Flooz",
                        COUNT(CASE WHEN TransferCarrier = "Tmoney" THEN "Tmoney" ELSE NULL END) as "Tmoney"
                    '))
                    ->where('AllocationPayNumber', $i)
                    ->where('reg_fa', $payement->reg_fa)
                    ->where('pref_fa', $payement->pref_fa)           
                    ->where('comm_fa', $payement->comm_fa)
                    ->where('cant_fa', $payement->cant_fa)
                    ->where('vill_fa', $payement->vill_fa)
                    ->where('financement', $payement->financement)
                    ->groupBy('reg_fa', 'pref_fa', 'comm_fa', 'cant_fa', 'vill_fa','financement')
                    ->get();

                foreach ($pay as $paye) {
                    $lignes_excel['nbr'.$i] = $paye->benef*1;                                
                    $lignes_excel['effectFem'.$i] = $paye->Femmes*1;
                    $lignes_excel['effectHom'.$i] = $paye->Hommes*1;

                    $lignes_excel['date'.$i] = ""; 
                    $lignes_excel['datePrevu'.$i] = match ($i) {
                        1, 2 => $date1->format('Y-m-d'),
                        3 => $date2->format('Y-m-d'),
                        4, 5 => $date3->format('Y-m-d'),
                        6 => $date4->format('Y-m-d'),
                    };

                    $lignes_excel['ressources'.$i] = $paye->montant*1;
                    $lignes_excel['totalFrais'.$i] = $paye->frais*1; 
                    $lignes_excel['fraisFlooz'.$i] = $paye->fraisFlooz*1;
                    $lignes_excel['fraisTmoney'.$i] = $paye->fraisTmoney*1;
                    $lignes_excel['effectifPayeFlooz'.$i] = $paye->Flooz*1;
                    $lignes_excel['effectifPayeTmoney'.$i] = $paye->Tmoney*1;
                    $lignes_excel['comment'.$i] = "";                     
                }
            }

            // Insertion dans la base de données
            DB::table('etat_ptf_village')->insert($lignes_excel);
        }  
    }


    public function etat_par_beneficiaire(){

        DB::table('etat_par_beneficiaires')->truncate();

        ini_set('max_execution_time', 0); // 5 minutes

        DB::insert("
            INSERT INTO etat_par_beneficiaires(id,reg,pref,comm,cant,vill,financement,nom,prenom,sexe,telephone,cardNum,montantEnv,
            montantRecu,frais,nbrTranche,montant1,frais1,montant2,frais2,montant3,frais3,montant4,frais4,montant5,frais5,montant6,frais6)
            SELECT e.id,p.reg_fa,p.pref_fa,p.comm_fa,p.cant_fa,p.vill_fa,p.financement, e.nom,e.prenom,e.sexe, e.telephone,e.card_number,e.SommeTM,
            SUM(p.montantR) AS montantRecu,
            (e.SommeTM-SUM(p.montantR)) AS frais,
            COUNT(p.AllocationPayNumber) nbrTranche,
            SUM(CASE WHEN p.AllocationPayNumber = 1 THEN p.montantR ELSE 0 END) AS montant1, 
            SUM(CASE WHEN p.AllocationPayNumber = 1 THEN p.frais ELSE 0 END) AS frais1,
            SUM(CASE WHEN p.AllocationPayNumber = 2 THEN p.montantR ELSE 0 END) AS montant2,
            SUM(CASE WHEN p.AllocationPayNumber = 2 THEN p.frais ELSE 0 END) AS frais2,
            SUM(CASE WHEN p.AllocationPayNumber = 3 THEN p.montantR ELSE 0 END) AS montant3,
            SUM(CASE WHEN p.AllocationPayNumber = 3 THEN p.frais ELSE 0 END) AS frais3,
            SUM(CASE WHEN p.AllocationPayNumber = 4 THEN p.montantR ELSE 0 END) AS montant4,
            SUM(CASE WHEN p.AllocationPayNumber = 4 THEN p.frais ELSE 0 END) AS frais4,
            SUM(CASE WHEN p.AllocationPayNumber = 5 THEN p.montantR ELSE 0 END) AS montant5,
            SUM(CASE WHEN p.AllocationPayNumber = 5 THEN p.frais ELSE 0 END) AS frais5,
            SUM(CASE WHEN p.AllocationPayNumber = 6 THEN p.montantR ELSE 0 END) AS montant6,
            SUM(CASE WHEN p.AllocationPayNumber = 6 THEN p.frais ELSE 0 END) AS frais6 
            FROM etatpaiements e
            INNER JOIN ptf_villages p ON e.id = CONCAT(p.TransferNumber,';',p.CardNum)
            GROUP BY e.id,p.reg_fa,p.pref_fa,p.comm_fa,p.cant_fa,p.vill_fa,p.financement, e.nom,e.prenom,e.sexe, e.telephone,e.card_number,e.SommeTM;
        ");
    }

    //Etat du contrat et du site une fois la suspension a eu lieu
    public function etat_contrat()
    {
        $contrat = Contrat::where('date_fin', '<=', now())
                            ->where('statu','=', 'SUSPENDU')
                            ->first();

        if($contrat) {
            $demande = Demande::join('sites', 'demandes.site_id', '=', 'sites.id')
                        ->join('ouvrages', 'sites.id', '=', 'ouvrages.site_id')
                        ->join('signers', 'ouvrages.id', '=', 'signers.ouvrage_id')
                        ->join('contrats', 'contrats.id', '=', 'signers.contrat_id')
                        ->select('contrats.id', 'demandes.date_debut_old', 'demandes.date_fin_old', 'demandes.nbJr')
                        ->where('contrats.id', $contrat->id)
                        ->first();

                // Parcourir chaque contrat et mettre à jour la date_debut et la date_fin
                $date_fin = Carbon::parse($demande->date_fin_old)->addDays($demande->nbJr);  // Ajouter 10 jours
                $date_debut = Carbon::parse($demande->date_debut_old)->addDays($demande->nbJr);  // Ajouter 10 jours
                
                if($demande) {
                    // Trouver le contrat et mettre à jour la date_fin
                    $contratToUpdate = Contrat::findOrFail($demande->id);
                    $contratToUpdate->date_debut = $date_debut->toDateString();  // Mettre à jour la nouvelle date
                    $contratToUpdate->date_fin = $date_fin->toDateString();  // Mettre à jour la nouvelle date
                    $contratToUpdate->statu = "NON_SUSPENDU";  // Mettre à jour la nouvelle date
                    $contratToUpdate->save();  // Sauvegarder les changements dans la base de données
                }
            // Mettre à jour le statut des sites correspondants
            DB::table('sites')
            ->whereIn('id', function ($query) use ($contrat) {
                $query->select('ouvrages.site_id')
                    ->from('signers')
                    ->join('ouvrages', 'signers.ouvrage_id', '=', 'ouvrages.id')
                    ->join('contrats', 'signers.contrat_id', '=', 'contrats.id')
                    ->where('signers.contrat_id', '=', $contrat->id);
            })
            ->update(['statu' => 'EC']);
        }
        
    }

    public function etat_site(){

        $demande = Demande::where('date_debut_susp', '<=', now())
                            ->where('statu','=', 'Approuvé')
                            ->first();
        
        // $contrat = Contrat::where('statu','=', 'SUSPENDU')
        //                     ->first();

        if($demande){
            // Mettre à jour le statut des sites correspondants
            $site = Site::findOrFail($demande->site_id);
            $site->statu = "SUSPENDU";
            $site->save();

            // Récupérer les contrats associés
            $contrats = Contrat::join('signers', 'contrats.id', '=', 'signers.contrat_id')
                ->join('ouvrages', 'ouvrages.id', '=', 'signers.ouvrage_id')
                ->join('sites', 'ouvrages.site_id', '=', 'sites.id')
                ->where('sites.id', $demande->site_id)
                ->select('contrats.id')
                ->get();

            // Parcourir chaque contrat et mettre à jour les dates
            foreach ($contrats as $contrat) {
                $date_fin_suppr = Carbon::parse($demande->date_debut_susp)->addDays($demande->nbJr);  // Ajouter les jours
                // Trouver le contrat et mettre à jour les dates
                $contratToUpdate = Contrat::find($contrat->id);
                if ($contratToUpdate) { // Vérifier si le contrat existe
                    $contratToUpdate->date_debut = $demande->date_debut_susp;  // Mettre à jour la date de début
                    $contratToUpdate->date_fin = $date_fin_suppr->toDateString();  // Mettre à jour la date de fin
                    $contratToUpdate->statu = "SUSPENDU";  // Mettre à jour le statut
                    $contratToUpdate->save();  // Sauvegarder les changements
                }
            }
        }
    }

}
