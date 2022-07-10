<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Models\Fleet;
use App\Models\SignImage;

use App\Mail\MMRMail;
use App\Imports\FleetsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

use DateTime;

class FleetController extends Controller
{
    public function getFleets(Request $request)
    {
        $this->authorize('manage-fleet');

        $company_id = Auth::user()->company_id;

        $tractor_id = $request->input('tractor-id') ?? '';
        $model = $request->input('model') ?? '';
        $service_provider = $request->input('service_provider') ?? '';
       
        $fleets = DB::select("
                                SELECT
                                    f.*,
                                    DATE_FORMAT(f.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN f.bit <= CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN f.bit <= CURDATE() AND DATE_ADD(f.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM fleets AS f
                                WHERE f.tractor_id LIKE '%{$tractor_id}%' AND 
                                        f.model LIKE '%{$model}%' AND 
                                        f.service_provider LIKE '%{$service_provider}%' AND
                                        f.company_id = {$company_id}
                            ");
        
        return view('fleets.list', compact('fleets', 'tractor_id', 'model', 'service_provider'));
    }

    public function getFleet(Request $request)
    {
        $this->authorize('manage-fleet');

        $company_id = Auth::user()->company_id;

        $id = $request->input('id');
        $fleets = DB::select("
                                SELECT
                                    f.*,
                                    DATE_FORMAT(f.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN f.bit <= CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN f.bit <= CURDATE() AND DATE_ADD(f.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM fleets AS f
                                WHERE f.id = {$id} AND f.company_id = {$company_id}
                            ");
        if ($fleets == null || count($fleets) != 1) {
            return response()->json([
                'type'      => 'failed',
                'message'   => "Can't find the fleet info by id = {$id}"
            ]);
        } else {
            $fleet = $fleets[0];
            return response()->json([
                'type'      => 'success',
                'fleet'     => $fleet
            ]);
        }
    }

    public function saveFleet(Request $request)
    {
        $this->authorize('manage-fleet');

        $company_id = Auth::user()->company_id;

        $id = $request->input('id');
        $existed = Fleet::where('tractor_id', $request->input('tractor_id'))->get();

        if ($id) {
            $fleet = Fleet::find($id);

            if (count($existed) > 0 && $existed[0]->id != $id) {
                $request->session()->flash('error', 'Does exist Tractor # already ! (Tractor #: ' . $request->input('tractor_id') . ')');
                return Redirect::back()->withInput();
            }

            $fleet->tractor_id          = $request->input('tractor_id');
            $fleet->model               = $request->input('model') ?? '';
            $fleet->vin                 = $request->input('vin') ?? '';
            $fleet->year                = $request->input('year');
            $fleet->license_plate       = $request->input('license_plate') ?? '';
            $fleet->t_check             = $request->input('t_check') ?? '';
            $fleet->pre_pass            = $request->input('pre_pass') ?? '';
            $fleet->service_provider    = $request->input('service_provider') ?? '';
            $fleet->qiv                 = $request->input('qiv') ?? '';
            $fleet->bit                 = $request->input('bit');
            $fleet->domicile            = $request->input('domicile') ?? '';
            $fleet->domicile_email      = $request->input('domicile_email') ?? '';
            $fleet->book_value          = $request->input('book_value') ?? 0;
            $fleet->vedr                = $request->input('vedr') ?? '';
            $fleet->eld                 = $request->input('eld') ?? '';

            $fleet->save();

            $request->session()->flash('success', 'Was updated successfully ! (Tractor #: ' . $fleet->tractor_id . ')');
            return redirect('fleet/list');
        } else {
            if (count($existed) > 0) {
                $request->session()->flash('error', 'Does exist Tractor # already ! (Tractor #: ' . $request->input('tractor_id') . ')');
                return Redirect::back()->withInput();
            }

            $fleet = new Fleet();
            $fleet->tractor_id          = $request->input('tractor_id');
            $fleet->model               = $request->input('model') ?? '';
            $fleet->vin                 = $request->input('vin') ?? '';
            $fleet->year                = $request->input('year');
            $fleet->license_plate       = $request->input('license_plate') ?? '';
            $fleet->t_check             = $request->input('t_check') ?? '';
            $fleet->pre_pass            = $request->input('pre_pass') ?? '';
            $fleet->service_provider    = $request->input('service_provider') ?? '';
            $fleet->qiv                 = $request->input('qiv') ?? '';
            $fleet->bit                 = $request->input('bit');
            $fleet->domicile            = $request->input('domicile') ?? '';
            $fleet->domicile_email      = $request->input('domicile_email') ?? '';
            $fleet->book_value          = $request->input('book_value') ?? 0;
            $fleet->vedr                = $request->input('vedr') ?? '';
            $fleet->eld                 = $request->input('eld') ?? '';
            $fleet->company_id          = $company_id;

            $fleet->save();

            $request->session()->flash('success', 'Was saved successfully ! (Tractor #: ' . $fleet->tractor_id . ')');
            return redirect('fleet/list');
        }
    }

    public function editFleet(Request $request)
    {
        $this->authorize('manage-fleet');

        $company_id = Auth::user()->company_id;

        $id = $request->route()->parameter('id');
        $fleets = DB::select("
                                SELECT
                                    f.*,
                                    DATE_FORMAT(f.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN f.bit <= CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN f.bit <= CURDATE() AND DATE_ADD(f.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM fleets AS f
                                WHERE f.id = {$id} AND f.company_id = {$company_id}
                            ");
        if ($fleets == null || count($fleets) != 1) {
            return response()->json([
                'type'      => 'failed',
                'message'   => "Can't find the tractor info by id = {$id}"
            ]);
        } else {
            $fleet = $fleets[0];
            return view('fleets.fleet', [
                'fleet' => $fleet
            ]);
        }
    }

    public function removeFleet(Request $request)
    {
        $this->authorize('manage-fleet');

        $id = $request->route()->parameter('id');
        
        $res = Fleet::find($id)->delete();
        if ($res) {
            $request->session()->flash('success', 'Fleet removed successfully. (ID: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this fleet at this time. (ID: ' . $id . ') Please retry later.');
        }
        return redirect('/fleet/list');
    }

    public function uploadFleets(Request $request)
    {
        $this->authorize('manage-fleet');

        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            $Imports = new FleetsImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('success', 'Fleets were imported from <strong>' . $file_name . '</strong> successfully!');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return redirect('fleet/list');
    }

    public function mmrIndex()
    {
        $this->authorize('manage-fleet');

        $fleets = Fleet::get()->all();
        $signs = SignImage::get()->all();

        return view('fleets.mmr', compact('fleets', 'signs'));
    }

    public function uploadSigns(Request $request)
    {
        $this->authorize('manage-fleet');

        $company_id = Auth::user()->company_id;

        $files = "";

        if($request->hasfile('upload-files')) {
            foreach($request->file('upload-files') as $file) {
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $save_name = pathinfo($name, PATHINFO_FILENAME);

                // save in storage/app/public/signs/company_id
                $path = $file->storeAs("public/signs/{$company_id}", $name);

                $files .= $name . ", ";

                $signs = SignImage::where('name', $save_name)->get()->all();

                if (!$signs) {
                    $sign = new SignImage();
                    $sign->company_id = $company_id;
                } else {
                    $sign = $signs[0];
                }

                $sign->name         = $save_name;
                $sign->extension    = $ext;
                $sign->path         = $path;
                $sign->save();
            }
            $request->session()->flash('success', 'Uploaded Sign Image Files: <strong>' . implode(', ', explode(', ', $files, -1)) . '</strong>');
        } else {
            $request->session()->flash('error', 'Please choose sign image files!');
        }

        return redirect('mmr');
    }

    public function sendEmailMMR(Request $request)
    {
        $this->authorize('manage-fleet');
        
        $yearNum        = $request->input('year-num');
        $monthNum       = $request->input('month-num');
        
        //get month name as November from month num
        $dateObj        = DateTime::createFromFormat('!m', $monthNum);
        $monthName      = $dateObj->format('F');
        $mYearMonth     = $monthName . ' ' . $request->input('year-num');

        $sign_image     = SignImage::where('name', $request->input('sign'))->get();
        if (empty($sign_image) || count($sign_image) > 1) {
            $request->session()->flash('error', 'Can\'t find the sign data with Name = ' . $request->input('sign'));
            return Redirect('/mmr');
        }
        $sign           = storage_path('app/' . $sign_image[0]->path);

        $completedDate  = $request->input('completed-date');
        $hasMaints      = $request->input('maintenance');
        $oServices      = $request->input('out-of-service');

        $tIds           = $request->input('tractor-id');
        if (empty($tIds)) {
            $request->session()->flash('error', 'Please add tractors/maintenances.');
            return Redirect('/mmr');
        }

        $cMileages      = $request->input('current-mileage');
        $maintDates     = $request->input('maintenance-date');
        $maintDescs     = $request->input('maintenance-desc');

        $tractors = [];
        $newIds = [];
        foreach ($tIds as $t) {
            if (!in_array($t, $newIds)) {
                array_push($newIds, $t);
            }
        }

        foreach ($newIds as $nId) {
            $tractor = new \stdClass();
            $tractor->id = $nId;

            $tractor->hasMaint = false;
            $tractor->oService = false;
            $tractor->cMileage = 0;

            $fleet = Fleet::where('tractor_id', $tractor->id)->get();
            if (empty($fleet) || count($fleet) > 1) {
                $request->session()->flash('error', 'Can\'t find the fleet data with ID = ' . $nId);
                return Redirect('/mmr');
            }
            $tractor->domicile      = $fleet[0]->domicile;
            $tractor->email         = $fleet[0]->domicile_email;
            $tractor->sProvider     = $fleet[0]->service_provider;
            
            $idxs = [];
            foreach ($tIds as $i => $t) {
                if ($t == $nId && in_array($t, $newIds)) {
                    array_push($idxs, $i);
                }
            }

            $tractor->maints = [];
            foreach ($idxs as $i) {
                $tractor->hasMaint = $tractor->hasMaint || $hasMaints[$i];
                $tractor->oService = $tractor->oService || $oServices[$i];
                $tractor->cMileage = $cMileages[$i];

                $m = new \stdClass();
                // $m->mDate = date('n-j-Y', strtotime($maintDates[$i]));
                $m->mDate = $maintDates[$i];
                $m->mDesc = $maintDescs[$i];
                array_push($tractor->maints, $m);
            }

            array_push($tractors, $tractor);
        }
        
        //set storage path
        $path = 'public/mmr/' . $yearNum . (($monthNum < 10) ? '0' . $monthNum : $monthNum) . '/';

        //bulk email
        $bulks = [];

        foreach ($tractors as $t) {
            $data = [
                'mYearMonth'    => $mYearMonth,
                'sign'          => $sign,
                // 'cDate'         => date('n-j-Y', strtotime($completedDate)),
                'cDate'         => $completedDate,
                'tractor'       => $t
            ];

            // save to storage/app
            $pdf = PDF::loadView('fleets.mmr-template', $data)
                        ->setPaper('Letter');
            $file = $path . 'MMR-' . $t->id . '.pdf';

            Storage::put($file, $pdf->output());

            if (!array_key_exists($t->email, $bulks)) {
                $bulks[$t->email]['files'][] = 'app/' . $file;
                $bulks[$t->email]['tractors'][] = $t->id;
            } else {
                array_push($bulks[$t->email]['files'], 'app/' . $file);
                array_push($bulks[$t->email]['tractors'], $t->id);
            }
        }
        
        // send email with bulk attachments to domicile address
        foreach ($bulks as $email => $attachments) {
            if (!empty($email)) {
                Mail::to($email)
                    ->send(new MMRMail($mYearMonth, $attachments['files'], $attachments['tractors']));
            }
        }

        $request->session()->flash('success', 'Sent emails with MMR PDFs.');
        return Redirect('/mmr');
    }
}
