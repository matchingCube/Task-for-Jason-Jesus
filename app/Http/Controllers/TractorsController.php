<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tractors;
use DB;
use DateTime;
use stdClass;

class TractorsController extends Controller
{
    //
    public function get_tractors() {
        $tractors = DB::select("
                                SELECT
                                    t.*,
                                    DATE_FORMAT(t.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN t.bit <= CURDATE() AND CURDATE() <= DATE_ADD(t.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN t.bit <= CURDATE() AND DATE_ADD(t.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(t.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM tractors AS t");
        return view('tractors.list', [
            'tractors' => $tractors
        ]);
    }
    public function get_tractor(Request $request) {
        $id = $request->input('id');
        $tractors = DB::select("
                                SELECT
                                    t.*,
                                    DATE_FORMAT(t.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN t.bit <= CURDATE() AND CURDATE() <= DATE_ADD(t.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN t.bit <= CURDATE() AND DATE_ADD(t.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(t.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM tractors AS t
                                WHERE t.id = {$id}");
        if ($tractors == null || count($tractors) != 1) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the tractor info by id = {$id}"
            ]);
        } else {
            $tractor = $tractors[0];
            return response()->json([
                'type'      => 'success',
                'tractor'   => $tractor
            ]);
        }
    }
    public function save_tractor(Request $request) {
        $tractor = new stdClass();
        $tractor->id                    = $request->input('id');
        $tractor->tractor_id            = $request->input('tractor_id');
        $tractor->model                 = $request->input('model');
        $tractor->vin                   = $request->input('vin');
        $tractor->year                  = $request->input('year');
        $tractor->license_plate         = $request->input('license_plate');
        $tractor->last_bit_miles        = ($request->input('last_bit_miles')) ? $request->input('last_bit_miles') : 0;
        $tractor->bit                   = $request->input('bit');
        $tractor->oil_changes           = $request->input('oil_changes');
        $tractor->insurance_book_value  = ($request->input('insurance_book_value')) ? $request->input('insurance_book_value') : 0;
        $tractor->smart_witness_serial  = $request->input('smart_witness_serial');
        $tractor->omnitracs_device_id   = $request->input('omnitracs_device_id');
        $tractor->pre_pass              = $request->input('pre_pass');
        $tractor->t_check               = $request->input('t_check');
        if ($tractor->id) {
            //update
            Tractors::where([
                                'id' => $tractor->id
                            ])
                    ->update([
                        'tractor_id' => $tractor->tractor_id,
                        'model' => $tractor->model,
                        'vin' => $tractor->vin,
                        'year' => $tractor->year,
                        'license_plate' => $tractor->license_plate,
                        'last_bit_miles' => $tractor->last_bit_miles,
                        'bit' => $tractor->bit,
                        'oil_changes' => $tractor->oil_changes,
                        'insurance_book_value' => $tractor->insurance_book_value,
                        'smart_witness_serial' => $tractor->smart_witness_serial,
                        'omnitracs_device_id' => $tractor->omnitracs_device_id,
                        'pre_pass' => $tractor->pre_pass,
                        't_check' => $tractor->t_check
                    ]);
                $request->session()->flash('success', 'Was updated successfully ! (Tractor #: ' . $tractor->tractor_id . ')');
            return redirect('tractors');
        } else{
            $tractors = DB::select("SELECT t.* FROM tractors as t WHERE t.tractor_id = '{$tractor->tractor_id}'");
            if (count($tractors) > 0) {
                $request->session()->flash('error', 'Does exist Tractor # already ! (Tractor #: ' . $tractor->tractor_id . ')');
                return view('tractors.tractor', [
                    'tractor' => $tractor
                ]);
            } else {
                Tractors::insert([
                    'tractor_id'            => $tractor->tractor_id,
                    'model'                 => $tractor->model,
                    'vin'                   => $tractor->vin,
                    'year'                  => $tractor->year,
                    'license_plate'         => $tractor->license_plate,
                    'last_bit_miles'        => $tractor->last_bit_miles,
                    'bit'                   => $tractor->bit,
                    'oil_changes'           => $tractor->oil_changes,
                    'insurance_book_value'  => $tractor->insurance_book_value,
                    'smart_witness_serial'  => $tractor->smart_witness_serial,
                    'omnitracs_device_id'   => $tractor->omnitracs_device_id,
                    'pre_pass'              => $tractor->pre_pass,
                    't_check'               => $tractor->t_check
                ]);
                $request->session()->flash('success', 'Was added successfully ! (Tractor #: ' . $tractor->tractor_id . ')');
                return redirect('tractors');
            }
        }
    }
    public function edit_tractor(Request $request) {
        $id = $request->route()->parameter('id');
        $tractors = DB::select("
                                SELECT
                                    t.*,
                                    DATE_FORMAT(t.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN t.bit <= CURDATE() AND CURDATE() <= DATE_ADD(t.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN t.bit <= CURDATE() AND DATE_ADD(t.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(t.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM tractors AS t
                                WHERE t.id = {$id}");
        if ($tractors == null || count($tractors) != 1) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the tractor info by id = {$id}"
            ]);
        } else {
            $tractor = $tractors[0];
            return view('tractors.tractor', [
                'tractor' => $tractor
            ]);
        }
    }
    public function remove_tractor(Request $request) {
        $id = $request->route()->parameter('id');
        DB::delete("DELETE FROM tractors WHERE id = {$id}");
        $request->session()->flash('success', 'Was removed successfully ! (id: ' . $id . ')');
        return redirect('tractors');
    }
}
