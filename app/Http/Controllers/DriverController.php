<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Linehaul_Drivers;

use App\Imports\DriversImport;
use Maatwebsite\Excel\Facades\Excel;

class DriverController extends Controller
{
    public function getDrivers(Request $request)
    {
        $this->authorize('manage-driver');

        $work_status = $request->input('work-status') ?? 1;
        $driver_name = $request->input('driver-name') ?? '';
        
        $drivers = Linehaul_Drivers::where('work_status', $work_status)
                                    ->where('driver_name', 'like', '%' . $driver_name . '%')
                                    ->get()
                                    ->all();
                
        return view('drivers.list', compact('drivers', 'driver_name', 'work_status'));
    }

    public function getDriver(Request $request)
    {
        $this->authorize('manage-driver');

        $id = $request->input('id');
        $driver = Linehaul_Drivers::find($id);
        if (!$driver) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the driver info by id = {$id}"
            ]);
        } else {
            return response()->json([
                'type' => 'success',
                'driver' => $driver
            ]);
        }
    }

    public function editDriver(Request $request)
    {
        $this->authorize('manage-driver');
        
        $id = $request->route()->parameter('id');
        $driver = Linehaul_Drivers::find($id);
        if (!$driver) {
            return redirect('/drivers')->with('error', "Can't find the driver info by id = {$id}");
        } else {
            return view('drivers.edit', [
                'driver' => $driver
            ]);
        }
    }

    public function removeDriver(Request $request)
    {
        $this->authorize('manage-driver');

        $id = $request->route()->parameter('id');
        $driver = Linehaul_Drivers::find($id);

        Storage::delete('public/uploads/driver/' . $driver->photo);

        $res = $driver->delete();

        if ($res) {
            $request->session()->flash('success', 'Driver removed successfully. (Id: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this driver at this time. (Id: ' . $id . ') Please retry later.');
        }
        return redirect('drivers');
    }

    public function saveDriver(Request $request)
    {
        $this->authorize('manage-driver');
        
        $id = $request->input('id');

        if ($id) {
            $driver = Linehaul_Drivers::find($id);
        } else {
            $driver = new Linehaul_Drivers();
            $driver->company_id = Auth::user()->company_id;
        }

        $driver->driver_id          = $request->input('driver_id');
        $driver->driver_name        = $request->input('driver_name');
        $driver->email              = $request->input('email');
        $driver->phone              = $request->input('phone');
        $driver->license            = $request->input('license');
        $driver->address            = $request->input('address');
        $driver->price_per_mile     = $request->input('price_per_mile');
        $driver->work_status        = $request->input('work_status');

        $existed = Linehaul_Drivers::where('driver_id', $request->input('driver_id'))
                                    ->get()
                                    ->all();

        if (!$existed || $id == $existed[0]->id) {
            // save driver info
            if($request->hasfile('photo')) {
                //delete old photo
                if (isset($driver->photo) && $driver->photo) {
                    Storage::delete('public/uploads/driver/' . $driver->photo);
                }
                
                $file = $request->file('photo');
            
                $path_parts = pathinfo($file->getClientOriginalName());
                
                $string = preg_replace(array('/\s+/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $path_parts['filename']);
                $clean_name = strtolower($string);
                $filename = $clean_name . '_' . uniqid() . '.' . $path_parts['extension'];
                $path = $file->storeAs('public/uploads/driver', $filename);
            } else {
                if (!$request->input('photo-link')) {
                    Storage::delete('public/uploads/driver/' . $driver->photo);
                    $filename = null;    
                } else {
                    $filename = $driver->photo;
                }
            }
    
            $driver->photo = $filename;

            $driver->save();
            $request->session()->flash('success', 'Driver info was saved successfully. (ID: ' . $driver->driver_id . ')');
            return redirect('drivers');
        } else {
            // in case exists the driver with the same driver id
            $driver->photo = null;
            $request->session()->flash('error', 'Exists already the driver with the same driver ID (' . $request->input('driver_id') . ')');
            return view('drivers.edit', compact('driver', $driver));
        }        
    }

    public function removeBulkDrivers(Request $request)
    {
        $this->authorize('manage-driver');

        $checkedDrivers = $request->input('checked-drivers');
        $res = Linehaul_Drivers::destroy($checkedDrivers);
        
        if ($res) {
            $request->session()->flash('success', 'Drivers(#' . implode(',', $checkedDrivers) .') were removed successfully.');
        } else {
            $request->session()->flash('error', 'Can\'t remove these drivers at this time. Please retry later.');
        }
        return redirect('drivers');
    }

    public function uploadDrivers(Request $request)
    {
        $this->authorize('manage-driver');
        
        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            $Imports = new DriversImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('success', 'Drivers were imported from <strong>' . $file_name . '</strong> successfully !');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return redirect('drivers');
    }
}
