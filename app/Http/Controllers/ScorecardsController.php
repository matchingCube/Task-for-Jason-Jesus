<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Models\Persons;
use App\Models\Scorecards;
use App\Models\Person_Photo;

use App\Mail\ScorecardsMail;

class ScorecardsController extends Controller
{   
    public function get_scorecard(Request $request)
    {
        $this->authorize('manage-driver');
        
        $company_id = Auth::user()->company_id;

        $id = $request->route()->parameter('id');
        $persons = DB::select("
                                SELECT 
                                    p1.*,
                                    CONCAT(p2.name, '.', p2.ext) AS photo,
                                (CASE
                                    WHEN DATEDIFF(p1.mec, CURDATE()) <= -365 THEN 'text-danger'
                                    WHEN DATEDIFF(p1.mec, CURDATE()) > 0 AND DATEDIFF(p1.mec, CURDATE()) <= 30 THEN 'text-warning'
                                    ELSE ''
                                END) AS mec_color,
                                (CASE
                                    WHEN DATEDIFF(p1.mvr, CURDATE()) <= -365 THEN 'text-danger'
                                    WHEN DATEDIFF(p1.mvr, CURDATE()) > 0 AND DATEDIFF(p1.mvr, CURDATE()) <= 30 THEN 'text-warning'
                                    ELSE ''
                                END) AS mvr_color,
                                (CASE
                                    WHEN DATEDIFF(p1.cov, CURDATE()) <= -365 THEN 'text-danger'
                                    WHEN DATEDIFF(p1.cov, CURDATE()) > 0 AND DATEDIFF(p1.cov, CURDATE()) <= 30 THEN 'text-warning'
                                    ELSE ''
                                END) AS cov_color
                                FROM persons AS p1
                                LEFT JOIN person_photo AS p2 ON p2.name = p1.name AND p2.company_id = {$company_id}
                                WHERE p1.id = {$id} AND p1.company_id = {$company_id}
                            ");
        $person = $persons[0];
        
        $person->drug_test = date("F j, Y", strtotime($person->drug_test));
        $person->birth = date("F j, Y", strtotime($person->birth));
        $person->mec = date("F j, Y", strtotime($person->mec));
        $person->mvr = date("F j, Y", strtotime($person->mvr));
        $person->cov = date("F j, Y", strtotime($person->cov));
        if ($person->photo == null || $person->photo == '' ) {
            $person->photo = 'default.jpg';
        }

        $scorecard = Scorecards::select("scorecards.*")
                                ->where('person_id', '=', $id)
                                ->get();
        return view('scorecards.card', [
            'person'    => $person,
            'scorecard' => $scorecard
        ]);
    }

    public function get_persons() 
    {
        $this->authorize('manage-driver');

        $company_id = Auth::user()->company_id;

        $persons = DB::select("
                                SELECT 
                                    p1.*,
                                    CONCAT(p2.name, '.', p2.ext) AS photo,
                                    (CASE
                                        WHEN DATEDIFF(p1.mec, CURDATE()) <= -365 THEN 'text-danger'
                                        WHEN DATEDIFF(p1.mec, CURDATE()) > 0 AND DATEDIFF(p1.mec, CURDATE()) <= 30 THEN 'text-warning'
                                        ELSE ''
                                    END) AS mec_color,
                                    (CASE
                                        WHEN DATEDIFF(p1.mvr, CURDATE()) <= -365 THEN 'text-danger'
                                        WHEN DATEDIFF(p1.mvr, CURDATE()) > 0 AND DATEDIFF(p1.mvr, CURDATE()) <= 30 THEN 'text-warning'
                                        ELSE ''
                                    END) AS mvr_color,
                                    (CASE
                                        WHEN DATEDIFF(p1.cov, CURDATE()) <= -365 THEN 'text-danger'
                                        WHEN DATEDIFF(p1.cov, CURDATE()) > 0 AND DATEDIFF(p1.cov, CURDATE()) <= 30 THEN 'text-warning'
                                        ELSE ''
                                    END) AS cov_color
                                FROM persons AS p1
                                LEFT JOIN person_photo AS p2 ON p2.name = p1.name AND p2.company_id = {$company_id}
                                WHERE p1.company_id = {$company_id}
                                ORDER BY p1.id
                            ");

        foreach ($persons as $person) {
            $person->drug_test = date("F j, Y", strtotime($person->drug_test));
            $person->birth = date("F j, Y", strtotime($person->birth));
            $person->mec = date("F j, Y", strtotime($person->mec));
            $person->mvr = date("F j, Y", strtotime($person->mvr));
            $person->cov = date("F j, Y", strtotime($person->cov));

            if ($person->photo == null || $person->photo == '' ) {
                $person->photo = 'default.jpg';
            }
        }
        
        return view('scorecards.persons', [
            'persons' => $persons
        ]);
    }

    public function send_email(Request $request)
    {
        $this->authorize('manage-driver');
        
        $company_id = Auth::user()->company_id;

        $id = $request->input('person_id');
        $email = $request->input('email');

        $persons = DB::select("
                                SELECT 
                                    p1.*,
                                    CONCAT(p2.name, '.', p2.ext) AS photo,
                                (CASE
                                    WHEN DATEDIFF(p1.mec, CURDATE()) <= -365 THEN 'text-danger'
                                    WHEN DATEDIFF(p1.mec, CURDATE()) > 0 AND DATEDIFF(p1.mec, CURDATE()) <= 30 THEN 'text-warning'
                                    ELSE ''
                                END) AS mec_color,
                                (CASE
                                    WHEN DATEDIFF(p1.mvr, CURDATE()) <= -365 THEN 'text-danger'
                                    WHEN DATEDIFF(p1.mvr, CURDATE()) > 0 AND DATEDIFF(p1.mvr, CURDATE()) <= 30 THEN 'text-warning'
                                    ELSE ''
                                END) AS mvr_color,
                                (CASE
                                    WHEN DATEDIFF(p1.cov, CURDATE()) <= -365 THEN 'text-danger'
                                    WHEN DATEDIFF(p1.cov, CURDATE()) > 0 AND DATEDIFF(p1.cov, CURDATE()) <= 30 THEN 'text-warning'
                                    ELSE ''
                                END) AS cov_color
                                FROM persons AS p1
                                LEFT JOIN person_photo AS p2 ON p2.name = p1.name AND p2.company_id = {$company_id}
                                WHERE p1.id = {$id} AND p1.company_id = {$company_id}
                            ");
        $person = $persons[0];
        
        $person->drug_test = date("F j, Y", strtotime($person->drug_test));
        $person->birth = date("F j, Y", strtotime($person->birth));
        $person->mec = date("F j, Y", strtotime($person->mec));
        $person->mvr = date("F j, Y", strtotime($person->mvr));
        $person->cov = date("F j, Y", strtotime($person->cov));
        if ($person->photo == null || $person->photo == '' ) {
            $person->photo = 'default.jpg';
        }

        $scorecard = Scorecards::select("scorecards.*")
                                ->where('person_id', '=', $id)
                                ->get();
        Mail::to($email)
            ->send(new ScorecardsMail($person, $scorecard));

        return response()->json([
                                    'type' => 'success'
                                ]);
    }

    public function remove_person(Request $request)
    {
        $this->authorize('manage-driver');
        
        $id = $request->route()->parameter('id');

        $name = Persons::where('id', $id)
                            ->value('name');
        $photo_ext = Person_Photo::where('name', $name)
                            ->value('ext');

        $result = Persons::where('id', $id)
                            ->delete();
        $result = Scorecards::where('person_id', $id)
                            ->delete();
        $result = Person_Photo::where('name', $name)
                            ->delete();
        
        $image_path = public_path("media/photos/drivers/" . $name . "." . $photo_ext);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
        
        return redirect()->route('persons');
    }
}
