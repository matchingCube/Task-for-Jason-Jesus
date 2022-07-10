<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Linehaul_Trips;
use App\Models\Fuel_Purchases;
use App\Models\Linehaul_Drivers;
use App\Models\Other_Settlement_Adjustments;
use App\Models\Tractor_Repairs_Misc;
use App\Models\Person_Photo;

use App\Imports\ScorecardsImport;
use Maatwebsite\Excel\Facades\Excel;

use DateTime;

class UploadDataController extends Controller
{
    protected function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    protected function formatMonth($date) 
    {
        $array = explode('-', $date);
        if (count($array) == 3) {
            $array[1] = ucfirst(strtolower($array[1]));
            $date = implode('-', $array);
        }
        return $date;
    }

    public function index(Request $request)
    {
        $type = $request->route('type');
        if ($type == 'statement') {
            return view('upload.statement');
        } else if ($type == 'photo') {
            return view('upload.photo');
        } else if ($type == 'scorecards') {
            return view('upload.scorecards');
        } else {
            return view('chart.total_revenue_week');
        }
    }

    public function upload_statement(Request $request)
    {
        $this->authorize('manage-gf-statement');
        
        $file = $request->file('upload-file');
        if (null !== $request->get('selected-year')) {
            $year_num = $request->get('selected-year');
        } else {
            $year_num = date("Y");
        }
        if (null !== $request->get('selected-week')) {
            $week_num = $request->get('selected-week');
        } else {
            $week_num = date("W");
        }
        
        if ($file) {
            $origin_filename = $file->getClientOriginalName();

            if (($handle = fopen($file, "r")) !== FALSE) {
                $header1 = '';
                $header2 = '';
                $statement = array();
                $cnt_linehaul_trips                     = 0;
                $cnt_fuel_purchases                     = 0;
                $cnt_other_settlement_adjustments       = 0;
                $cnt_tractor_repairs_msic               = 0;
                $cnt_linehaul_drivers                   = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    switch ($data[0]) {
                        case "LINEHAUL TRIPS":
                            $header1 = trim($data[0]);
                            continue 2;
                        break;
                        // case "DATE":
                        //     $header2 = $data[0];
                        // break;
                        case "VEHICLE":
                            $statement['vehicle'] = trim($data[1]);
                            continue 2;
                        break;
                        // case "VEHICLE TOTALS:":
                        // break;
                        case "FUEL PURCHASES":
                            $header1 = trim($data[0]);
                            continue 2;
                        break;
                        case "TRACTOR REPAIRS/MISC":
                            $header1 = trim($data[0]);
                            continue 2;
                        break;
                        case "OTHER SETTLEMENT ADJUSTMENTS":
                            $header1 = trim($data[0]);
                            continue 2;
                        break;
                        case "** FUEL RECEIPTS USED FOR VARIABLE":
                            $header1 = "** FUEL RECEIPTS USED FOR VARIABLE";
                            continue 2;
                        case "Linehaul Drivers":
                            $header1 = trim($data[0]);
                            continue 2;
                        break;
                        case "Driver ID":
                            $header2 = trim($data[0]);
                            continue 2;
                        break;
                    }

                    if ($this->validateDate($this->formatMonth($data[0]), 'd-M-Y') ||
                        $this->validateDate($this->formatMonth($data[0]), 'j-M-y')) {
                        switch ($header1) {
                            case "LINEHAUL TRIPS":
                                $statement['date']              = $this->formatMonth($data[0]);
                                $statement['trip_id']           = trim($data[1]);
                                $statement['leg_org']           = trim($data[2]);
                                $statement['leg_dest']          = trim($data[3]);
                                $statement['zip_postal']        = trim($data[4]);
                                $statement['miles_qty']         = trim($data[5]);
                                $statement['vmr_rate']          = trim($data[6]);
                                $statement['mileage_plus']      = trim($data[7]);
                                $statement['premiums']          = trim($data[8]);
                                $statement['fuel']              = trim($data[9]);
                                $statement['total_rate']        = trim($data[10]);
                                $statement['amt_1']             = trim($data[11]);
                                $statement['pkgs']              = trim($data[12]);
                                $statement['amt_2']             = trim($data[13]);
                                $statement['d_and_h']           = trim($data[14]);
                                $statement['tolls']             = trim($data[15]);
                                $statement['flat_rate']         = trim($data[16]);
                                $statement['daily_gross_amt']   = trim($data[17]);
                                $statement['driver_1']          = trim($data[18]);
                                $statement['driver_2']          = trim($data[19]);

                                $trips = Linehaul_Trips::select([
                                                                'year_num',
                                                                'week_num',
                                                                'date', 
                                                                'vehicle', 
                                                                'trip_id'
                                                        ])
                                                        ->where([   
                                                                    'year_num'  => $year_num,
                                                                    'week_num'  => $week_num,
                                                                    'date'      => date('Y-m-d', strtotime($statement['date'])),
                                                                    'vehicle'   => $statement['vehicle'],
                                                                    'trip_id'   => $statement['trip_id']
                                                                ])
                                                        ->get();
                                if (count($trips) > 0) {
                                    Linehaul_Trips::where([
                                                                'year_num'          => $year_num,
                                                                'week_num'          => $week_num,
                                                                'date'              => date('Y-m-d', strtotime($statement['date'])),
                                                                'vehicle'           => $statement['vehicle'],
                                                                'trip_id'           => $statement['trip_id']
                                                            ])
                                                    ->update([
                                                                'leg_org'           => $statement['leg_org'],
                                                                'leg_dest'          => $statement['leg_dest'],
                                                                'zip_postal'        => $statement['zip_postal'],
                                                                'miles_qty'         => $statement['miles_qty'],
                                                                'vmr_rate'          => $statement['vmr_rate'],
                                                                'mileage_plus'      => $statement['mileage_plus'],
                                                                'premiums'          => $statement['premiums'],
                                                                'fuel'              => $statement['fuel'],
                                                                'total_rate'        => $statement['total_rate'],
                                                                'amt_1'             => $statement['amt_1'],
                                                                'pkgs'              => $statement['pkgs'],
                                                                'amt_2'             => $statement['amt_2'],
                                                                'd_and_h'           => $statement['d_and_h'],
                                                                'tolls'             => $statement['tolls'],
                                                                'flat_rate'         => $statement['flat_rate'],
                                                                'daily_gross_amt'   => $statement['daily_gross_amt'],
                                                                'driver_1'          => $statement['driver_1'],
                                                                'driver_2'          => $statement['driver_2']
                                                            ]);
                                } else {
                                    Linehaul_Trips::insert([
                                        'year_num'          => $year_num,
                                        'week_num'          => $week_num,
                                        'date'              => date('Y-m-d', strtotime($statement['date'])),
                                        'vehicle'           => $statement['vehicle'],
                                        'trip_id'           => $statement['trip_id'],
                                        'leg_org'           => $statement['leg_org'],
                                        'leg_dest'          => $statement['leg_dest'],
                                        'zip_postal'        => $statement['zip_postal'],
                                        'miles_qty'         => $statement['miles_qty'],
                                        'vmr_rate'          => $statement['vmr_rate'],
                                        'mileage_plus'      => $statement['mileage_plus'],
                                        'premiums'          => $statement['premiums'],
                                        'fuel'              => $statement['fuel'],
                                        'total_rate'        => $statement['total_rate'],
                                        'amt_1'             => $statement['amt_1'],
                                        'pkgs'              => $statement['pkgs'],
                                        'amt_2'             => $statement['amt_2'],
                                        'd_and_h'           => $statement['d_and_h'],
                                        'tolls'             => $statement['tolls'],
                                        'flat_rate'         => $statement['flat_rate'],
                                        'daily_gross_amt'   => $statement['daily_gross_amt'],
                                        'driver_1'          => $statement['driver_1'],
                                        'driver_2'          => $statement['driver_2'],
                                        'company_id'        => Auth::user()->company_id,
                                    ]);
                                }
                                $cnt_linehaul_trips++;
                            break;
                            case "OTHER SETTLEMENT ADJUSTMENTS":
                                $statement['date']          = $this->formatMonth($data[0]);
                                $statement['type']          = trim($data[1]);
                                $statement['description']   = trim($data[2]);
                                $statement['amt']           = (!empty(trim($data[3]))) ? trim($data[3]) : trim($data[13]);
                                $adjustments = Other_Settlement_Adjustments::select([
                                                        'year_num',
                                                        'week_num',
                                                        'date', 
                                                        'type',
                                                        'description'
                                                    ])
                                                    ->where([
                                                        'year_num'      => $year_num,
                                                        'week_num'      => $week_num,
                                                        'date'          => date('Y-m-d', strtotime($statement['date'])),
                                                        'type'          => $statement['type'],
                                                        'description'   => $statement['description'],
                                                    ])
                                                    ->get();
                                if (count($adjustments) > 0) {
                                    Other_Settlement_Adjustments::where([
                                                                    'year_num'      => $year_num,
                                                                    'week_num'      => $week_num,
                                                                    'date'          => date('Y-m-d', strtotime($statement['date'])),
                                                                    'type'          => $statement['type'],
                                                                    'description'   => $statement['description'],
                                                                ])
                                                                ->update([
                                                                    'amt'           => $statement['amt']
                                                                ]);
                                } else {
                                    Other_Settlement_Adjustments::insert([
                                        'year_num'      => $year_num,
                                        'week_num'      => $week_num,
                                        'date'          => date('Y-m-d', strtotime($statement['date'])),
                                        'type'          => $statement['type'],
                                        'description'   => $statement['description'],
                                        'amt'           => $statement['amt'],
                                        'company_id'    => Auth::user()->company_id,
                                    ]);
                                }
                                $cnt_other_settlement_adjustments++;
                            break;
                            case "FUEL PURCHASES":
                                $statement['date']                  = $this->formatMonth($data[0]);
                                $statement['ticket_check_id']       = trim($data[1]);
                                $statement['vehicle']               = trim($data[2]);
                                $statement['truck_stop']            = trim($data[3]);
                                $statement['city']                  = trim($data[4]);
                                $statement['state']                 = trim($data[5]);
                                $statement['qty']                   = trim($data[6]);
                                $statement['pur_amt']               = trim($data[7]);
                                $statement['auth_chgbk_arrears']    = is_numeric(trim($data[9])) ? trim($data[9]) : 0;
                                $statement['auth_chgbk_refund']     = is_numeric(trim($data[11])) ? trim($data[11]) : 0;
                                $statement['auth_chgbk_net']        = is_numeric(trim($data[13])) ? trim($data[13]) : 0;
                                $purchases = Fuel_Purchases::select([
                                                                    'year_num',
                                                                    'week_num',
                                                                    'date', 
                                                                    'ticket_check_id',
                                                                    'vehicle'
                                                                ])
                                                                ->where([
                                                                    'date'                  => date('Y-m-d', strtotime($statement['date'])),
                                                                    'ticket_check_id'       => $statement['ticket_check_id'],
                                                                    'vehicle'               => $statement['vehicle'],
                                                                ])
                                                    ->get();
                                if (count($purchases) > 0) {
                                    Fuel_Purchases::where([
                                                        'year_num'              => $year_num,
                                                        'week_num'              => $week_num,
                                                        'date'                  => date('Y-m-d', strtotime($statement['date'])),
                                                        'ticket_check_id'       => $statement['ticket_check_id'],
                                                        'vehicle'               => $statement['vehicle'],
                                                    ])
                                                    ->update([
                                                        'truck_stop'            => $statement['truck_stop'],
                                                        'city'                  => $statement['city'],
                                                        'state'                 => $statement['state'],
                                                        'qty'                   => $statement['qty'],
                                                        'pur_amt'               => $statement['pur_amt'],
                                                        'auth_chgbk_arrears'    => $statement['auth_chgbk_arrears'],
                                                        'auth_chgbk_refund'     => $statement['auth_chgbk_refund'],
                                                        'auth_chgbk_net'        => $statement['auth_chgbk_net']
                                                    ]);
                                } else {
                                    Fuel_Purchases::insert([
                                        'year_num'              => $year_num,
                                        'week_num'              => $week_num,
                                        'date'                  => date('Y-m-d', strtotime($statement['date'])),
                                        'ticket_check_id'       => $statement['ticket_check_id'],
                                        'vehicle'               => $statement['vehicle'],
                                        'truck_stop'            => $statement['truck_stop'],
                                        'city'                  => $statement['city'],
                                        'state'                 => $statement['state'],
                                        'qty'                   => $statement['qty'],
                                        'pur_amt'               => $statement['pur_amt'],
                                        'auth_chgbk_arrears'    => $statement['auth_chgbk_arrears'],
                                        'auth_chgbk_refund'     => $statement['auth_chgbk_refund'],
                                        'auth_chgbk_net'        => $statement['auth_chgbk_net'],
                                        'company_id'            => Auth::user()->company_id,
                                    ]);
                                }
                                $cnt_fuel_purchases++;
                            break;
                            case "TRACTOR REPAIRS/MISC":
                                $statement['date']                  = $this->formatMonth($data[0]);
                                $statement['ticket_check_id']       = $data[1];
                                $statement['vehicle']               = $data[2];
                                $statement['truck_stop']            = $data[3];
                                $statement['city']                  = $data[4];
                                $statement['state']                 = $data[5];
                                $statement['description']           = $data[6];
                                $statement['auth_chgbk_arrears']    = is_numeric($data[7]) ? $data[7] : 0;
                                $statement['auth_chgbk_refund']     = is_numeric($data[8]) ? $data[8] : 0;
                                $statement['repair_misc_amt']       = is_numeric($data[13]) ? $data[13] : 0;
                                $repairs = Tractor_Repairs_Misc::select([
                                                                    'year_num',
                                                                    'week_num',
                                                                    'date',
                                                                    'ticket_check_id',
                                                                    'vehicle'
                                                                ])
                                                                ->where([
                                                                    'year_num'              => $year_num,
                                                                    'week_num'              => $week_num,
                                                                    'date'                  => date('Y-m-d', strtotime($statement['date'])),
                                                                    'ticket_check_id'       => $statement['ticket_check_id'],
                                                                    'vehicle'               => $statement['vehicle'],
                                                                ])
                                                                ->get();
                                if (count($repairs) > 0) {
                                    Tractor_Repairs_Misc::where([
                                                                    'year_num'              => $year_num,
                                                                    'week_num'              => $week_num,
                                                                    'date'                  => date('Y-m-d', strtotime($statement['date'])),
                                                                    'ticket_check_id'       => $statement['ticket_check_id'],
                                                                    'vehicle'               => $statement['vehicle'],
                                                                ])
                                                                ->update([
                                                                    'truck_stop'            => $statement['truck_stop'],
                                                                    'city'                  => $statement['city'],
                                                                    'state'                 => $statement['state'],
                                                                    'description'           => $statement['description'],
                                                                    'auth_chgbk_arrears'    => $statement['auth_chgbk_arrears'],
                                                                    'auth_chgbk_refund'     => $statement['auth_chgbk_refund'],
                                                                    'repair_misc_amt'       => $statement['repair_misc_amt']
                                                                ]);
                                } else {
                                    Tractor_Repairs_Misc::insert([
                                                                    'year_num'              => $year_num,
                                                                    'week_num'              => $week_num,
                                                                    'date'                  => date('Y-m-d', strtotime($statement['date'])),
                                                                    'ticket_check_id'       => $statement['ticket_check_id'],
                                                                    'vehicle'               => $statement['vehicle'],
                                                                    'truck_stop'            => $statement['truck_stop'],
                                                                    'city'                  => $statement['city'],
                                                                    'state'                 => $statement['state'],
                                                                    'description'           => $statement['description'],
                                                                    'auth_chgbk_arrears'    => $statement['auth_chgbk_arrears'],
                                                                    'auth_chgbk_refund'     => $statement['auth_chgbk_refund'],
                                                                    'repair_misc_amt'       => $statement['repair_misc_amt'],
                                                                    'company_id'            => Auth::user()->company_id,
                                                                ]);
                                }
                                $cnt_tractor_repairs_msic++;
                            break;
                        }
                    }

                    if (is_numeric($data[0]) && $header1 == "Linehaul Drivers" && $header2 == "Driver ID") {
                        $statement['driver_id']     = trim($data[0]);
                        $statement['driver_name']   = trim($data[1]);
                        $drivers = Linehaul_Drivers::select(['driver_id', 'driver_name'])
                                                    ->where([
                                                        'driver_id' => $statement['driver_id'],
                                                    ])
                                                    ->get();
                        if (count($drivers) > 0) {
                            Linehaul_Drivers::where('driver_id', $statement['driver_id'])
                                            ->update(['driver_name' => $statement['driver_name']]);
                        } else {
                            Linehaul_Drivers::insert([
                                'driver_id'     => $statement['driver_id'],
                                'driver_name'   => $statement['driver_name'],
                                'company_id'    => Auth::user()->company_id,
                            ]);
                        }
                        $cnt_linehaul_drivers++;
                    }
                }
                $upload_status = '<div class="row"><div class="col-md-4 text-right">FILE NAME: </div>';
                $upload_status .= '<div class="col-md-8">' . $origin_filename . '</div></div>';
                $upload_status .= '<div class="row"><div class="col-md-4 text-right">LINEHAUL TRIPS: </div>';
                $upload_status .= '<div class="col-md-8">' . $cnt_linehaul_trips . ' rows</div></div>';
                $upload_status .= '<div class="row"><div class="col-md-4 text-right">FUEL PURCHASES: </div>';
                $upload_status .= '<div class="col-md-8">' . $cnt_fuel_purchases . ' rows</div></div>';
                $upload_status .= '<div class="row"><div class="col-md-4 text-right">OTHER SETTLEMENT ADJUSTMENTS: </div>';
                $upload_status .= '<div class="col-md-8">' . $cnt_other_settlement_adjustments . ' rows</div></div>';
                $upload_status .= '<div class="row"><div class="col-md-4 text-right">TRUCTOR REPAIRS/MISC: </div>';
                $upload_status .= '<div class="col-md-8">' . $cnt_tractor_repairs_msic . ' rows</div></div>';
                $upload_status .= '<div class="row"><div class="col-md-4 text-right">LINEHAUL DRIVERS: </div>';
                $upload_status .= '<div class="col-md-8">' . $cnt_linehaul_drivers . ' rows</div></div>';
                
                $request->session()->flash('status', $upload_status);
            }
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return view('upload.statement', [
            
        ]);
    }

    public function upload_photo(Request $request)
    {
        $this->authorize('manage-driver');

        $files = "";

        if($request->hasfile('upload-files')) {
            foreach($request->file('upload-files') as $file) {
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $save_name = pathinfo($name, PATHINFO_FILENAME);

                $file->move(public_path('media/photos/drivers'), $name);
                $files .= $name . ", ";

                Person_Photo::where([
                                        'name'          => $save_name,
                                        'ext'           => $ext
                                    ])
                            ->delete();
                Person_Photo::insert([
                                        'name'          => $save_name,
                                        'ext'           => $ext,
                                        'company_id'    => Auth::user()->company_id,
                                    ]);
            }
            $request->session()->flash('status', 'Uploaded Files: ' . $files);
        } else {
            $request->session()->flash('error', 'Please choose driver photo files !');
        }
        
        return view('upload.photo', [
            
        ]);
    }

    public function upload_scorecards(Request $request)
    {
        $this->authorize('manage-driver');

        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            $Imports = new ScorecardsImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('status', 'Scorecards were imported from <b>' . $file_name . '</b> successfully !');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        
        return redirect('/drivers/upload/scorecards');
    }

    public function check_st(Request $request)
    {
        $this->authorize('manage-gf-statement');

        $year = $request->input('year');
        $week = $request->input('week');
        
        $trips = Linehaul_Trips::where('year_num', $year)
                                ->where('week_num', $week)
                                ->get()
                                ->all();

        if ($trips == null || count($trips) == 0) {
            return response()->json([
                'type'  => 'failed',
                'msg'   => "Didn't upload statements for <strong>Week# {$week}, {$year}</strong> yet! <br> So you can upload new statements now."
            ]);
        } else {
            return response()->json([
                'type'  => 'success',
                'msg'   => "Uploaded statements for <strong>Week# {$week}, {$year}</strong> already! <br> If you upload statements, the data will be overwrited!"
            ]);
        }
    }
}
