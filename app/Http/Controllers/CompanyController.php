<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class CompanyController extends Controller
{
    public function list(Request $request)
    {
        $this->authorize('manage-company');

        $companies = Company::select('*');

        if ($request->has('company-brand') && !empty($request->input('company-brand'))) {
            $company_brand = $request->input('company-brand');
            $companies->where('brand', 'like', '%' . $company_brand . '%');
        } else {
            $company_brand = "";
        }

        if ($request->has('company-name') && !empty($request->input('company-name'))) {
            $company_name = $request->input('company-name');
            $companies->where('name', 'like', '%' . $company_name . '%');
        } else {
            $company_name = "";
        }

        if ($request->has('company-description') && !empty($request->input('company-description'))) {
            $company_description = $request->input('company-description');
            $companies->where('description', 'like', '%' . $company_description . '%');
        } else {
            $company_description = "";
        }
        
        $companies = $companies->get();
        
        return view('company.list', compact('companies', 'company_brand', 'company_name', 'company_description'));
    }

    public function getCompany(Request $request)
    {
        $this->authorize('manage-company');

        $id = $request->input('id');
        $company = Company::find($id);

        return response()->json([
            'type' => 'success',
            'company' => $company
        ]);
    }

    public function saveCompany(Request $request)
    {
        // $this->authorize('manage-company');

        if (!$request->has('brand') || 
            !$request->has('name')) {
            $request->session()->flash('error', "Sorry, your input not validation! Please check your input.");
            return back()->withInput();
        }

        $id = $request->input('id');
        $brand = $request->input('brand');
        $name = $request->input('name');
        $description = $request->input('description');
        
        if ($id) {
            $existed_company = Company::where('brand', '=', $brand)->get()->first();
            $company = Company::find($id);

            if ($existed_company && $existed_company->id != $company->id) {
                $request->session()->flash('error', "Sorry, brand already exists.");
                return back()->withInput();
            }

            if($request->hasfile('logo')) {
                //delete old logo
                if ($company->logo) {
                    Storage::delete('public/uploads/company/' . $company->logo);
                }
                
                $file = $request->file('logo');
            
                $path_parts = pathinfo($file->getClientOriginalName());
                
                $string = preg_replace(array('/\s+/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $path_parts['filename']);
                $clean_name = strtolower($string);
                $filename = $clean_name . '_' . uniqid() . '.' . $path_parts['extension'];
                $path = $file->storeAs('public/uploads/company', $filename);
            } else {
                $filename = $company->logo;
            }

            $company->name = $name;
            $company->brand = $brand;
            $company->description = $description;
            $company->logo = $filename;
            $company->save();

            $request->session()->flash('success', "Company was updated successfull!");
        } else {
            $company = Company::where('brand', '=', $brand)->get()->first();
            if ($company) {
                $request->session()->flash('error', "Sorry, brand already exists.");
                return back()->withInput();
            }

            if($request->hasfile('logo')) {
                $file = $request->file('logo');
            
                $path_parts = pathinfo($file->getClientOriginalName());
                
                $string = preg_replace(array('/\s+/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $path_parts['filename']);
                $clean_name = strtolower($string);
                $filename = $clean_name . '_' . uniqid() . '.' . $path_parts['extension'];
                $path = $file->storeAs('public/uploads/company', $filename);
            } else {
                $filename = null;
            }
            
            $company = Company::create([
                'brand' => $brand,
                'name' => $name,
                'description' => $description,
                'logo' => $filename
            ]);
    
            $request->session()->flash('success', "New company was created for " . $brand);
        }
        
        if (Auth::user()->role == 2) {
            return redirect()->route('company-edit', ['id' => $id]);
        }

        return $this->list($request);
    }

    public function editCompany(Request $request)
    {
        // $this->authorize('manage-company');

        $id = $request->route()->parameter('id');

        $company = Company::find($id);

        return view('company.company', compact('company'));
    }

    public function removeCompany(Request $request)
    {
        $this->authorize('manage-company');

        $id = $request->route()->parameter('id');

        $res = Company::find($id)->delete();

        if ($res) {
            $request->session()->flash('success', 'Company removed successfully. (ID: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this company at this time. (ID: ' . $id . ') Please retry later.');
        }
        return $this->list($request);
    }
}
