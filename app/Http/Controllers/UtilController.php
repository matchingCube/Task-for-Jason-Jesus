<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\External_Links;

use App\Imports\ExternalLinksImport;
use App\Exports\HistoricalDataExport;
use Maatwebsite\Excel\Facades\Excel;

use stdClass;

class UtilController extends Controller
{   
    public function get_ext_links() 
    {
        $this->authorize('manage-global-setting');

        $ext_links = External_Links::all();

        return view('util.ext_links.list', [
            'ext_links' => $ext_links
        ]);
    }

    public function get_ext_link(Request $request)
    {
        $this->authorize('manage-global-setting');

        $id = $request->input('id');

        $link = External_Links::find($id);
        
        if (!$link) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the link info by id = {$id}"
            ]);
        } else {
            $link->description = nl2br(e($link->description));
            return response()->json([
                'type' => 'success',
                'link' => $link
            ]);
        }
    }

    public function save_ext_link(Request $request)
    {
        $this->authorize('manage-global-setting');

        $link = new stdClass();
        $link->id           = $request->input('id');
        $link->name         = $request->input('name');
        $link->url          = str_replace(" ", "", $request->input('url'));
        $link->description  = $request->input('description');
        
        if ($link->id) {
            // update
            External_Links::where([
                                'id' => $link->id
                            ])
                    ->update([
                        'name'          => $link->name,
                        'url'           => $link->url,
                        'description'   => $link->description
                    ]);
                $request->session()->flash('success', 'Was updated successfully ! (Link #: ' . $link->id . ')');
            return redirect('util/ext-links');
        } else {
            // insert
            $links = External_Links::where([
                                            'url' => $link->url
                                        ])
                                    ->get();

            if (count($links) > 0) {
                $request->session()->flash('error', 'Does exist ebsite page with the same link already ! (URL: ' . $link->url . ')');
                return view('util.ext_links.link', [
                    'link' => $link
                ]);
            } else {
                $link_id = External_Links::insertGetId([
                    'name'          => $link->name,
                    'url'           => $link->url,
                    'description'   => $link->description,
                    'company_id'    => Auth::user()->company_id
                ]);
                $request->session()->flash('success', 'Was added successfully ! (Link #: ' . $link_id . ')');
                return redirect('util/ext-links');
            }
        }
    }

    public function edit_ext_link(Request $request)
    {
        $this->authorize('manage-global-setting');

        $id = $request->route()->parameter('id');

        $link = External_Links::find($id);
        
        if (!$link) {
            return redirect('/util/ext-links')->with('error', "Can't find the link info by id = {$id}");
        } else {
            return view('util.ext_links.link', [
                'link' => $link
            ]);
        }
    }

    public function remove_ext_link(Request $request)
    {
        $this->authorize('manage-global-setting');

        $id = $request->route()->parameter('id');
        External_Links::where([
                                'id' => $id
                            ])
                        ->delete();
        $request->session()->flash('success', 'Was removed successfully ! (Link #: ' . $id . ')');
        return redirect('util/ext-links');
    }

    public function truncate_ext_links(Request $request)
    {
        $this->authorize('manage-global-setting');
        
        External_Links::whereNotNull('id')->delete();
        
        $request->session()->flash('success', 'Was truncated successfully ! (All Links)');
        return redirect('util/ext-links');
    }

    public function upload_ext_links(Request $request)
    {
        $this->authorize('manage-global-setting');

        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            External_Links::whereNotNull('id')->delete();
            
            $Imports = new ExternalLinksImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('success', 'External links were imported from <b>' . $file_name . '</b> successfully !');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return redirect('util/ext-links');
    }

    public function download_data(Request $request)
    {
        $search = new stdClass();
        $search->from_year_num  = $request->input('from-year-num');
        $search->from_week_num  = $request->input('from-week-num');
        $search->to_year_num    = $request->input('to-year-num');
        $search->to_week_num    = $request->input('to-week-num');
        $search->key_metrics    = $request->input('key-metrics');
        
        $exports = new HistoricalDataExport($search);
        return Excel::download($exports, 'HD_WK' . $search->from_week_num . '-' . $search->from_year_num 
                                        . '_WK' . $search->to_week_num . '-' . $search->to_year_num . '.xlsx');
    }

    public function view_data(Request $request)
    {
        $search = new stdClass();
        $search->from_year_num  = $request->input('from-year-num');
        $search->from_week_num  = $request->input('from-week-num');
        $search->to_year_num    = $request->input('to-year-num');
        $search->to_week_num    = $request->input('to-week-num');
        $search->key_metrics    = $request->input('key-metrics');
        
        $compare_list = [];
        $view_names = [];
        foreach ($search->key_metrics as $key_metric) {
            if ($key_metric == 'revenue' || $key_metric == 'miles-total' || $key_metric == 'fuelcost-total') {
                array_push($compare_list, $key_metric);
            } else {
                array_push($view_names, $key_metric);
            }
        }
        if (!empty($compare_list)) {
            array_unshift($view_names, 'compare');
        }

        $data = array();
        foreach ($view_names as $view_name) {
            switch ($view_name) {
                case 'compare':
                    $data[$view_name] = get_data_compare($search, $compare_list);
                    break;
                case 'miles-driver':
                    $data[$view_name] = get_data_mile_driver($search);
                    break;
                case 'miles-vehicle':
                    $data[$view_name] = get_data_mile_vehicle($search);
                    break;
                case 'trips-driver':
                    $data[$view_name] = get_data_trips_driver($search);
                    break;
                case 'mpg-vehicle':
                    $data[$view_name] = get_data_mpg_vehicle($search);
                    break;
            }
        }
        return view('util.download_data.view_data', [
            'search'        => $search,
            'data'          => $data,
            'compare_list'  => $compare_list,
            'view_names'    => $view_names
        ]);
    }
}
