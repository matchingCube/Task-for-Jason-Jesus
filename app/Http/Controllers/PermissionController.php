<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Permission;
use App\Models\UserPermission;

class PermissionController extends Controller
{
    protected function getUsers()
    {
        if (Auth::user()->role == 1) {
            return User::with('permissions')->get()->all();
        } else {
            return User::with('permissions')
                        ->where('role', '>', 2)
                        ->where('company_id', '=', Auth::user()->company_id)
                        ->get();
        }
    }
    
    public function index(Request $request)
    {
        $this->authorize('manage-user');

        $users = $this->getUsers();
        
        $permissions = Permission::where('role', '>', Auth::user()->role)
                                ->orderBy('role', 'DESC')
                                ->orderBy('id', 'ASC')
                                ->get()
                                ->all();

        return view('permission.index', compact('users', 'permissions'));
    }

    public function update(Request $request)
    {
        $this->authorize('manage-user');

        $permissions = $request->input('permissions');
        
        $bulks = [];
        $user_ids = [];
        foreach ($permissions as $p) {
            $up = explode('-', $p);
            $user_ids[] = $up[0];
            $bulks[] = ['user_id' => $up[0], 'permission_id' => $up[1]];
        }
        
        UserPermission::whereIn('user_id', $user_ids)->delete();
        UserPermission::insert($bulks);

        $users = $this->getUsers();
        $permissions = Permission::where('role', '>', Auth::user()->role)
                                ->orderBy('role', 'DESC')
                                ->orderBy('id', 'ASC')
                                ->get()
                                ->all();

        $request->session()->flash('success', 'User permissions updated.');

        return view('permission.index', compact('users', 'permissions'));
    }
}
