<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\UserPermission;
use DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // please use this code
        // if you want to use roles and permissions from config
        /*
        $permissions = config('gftsetting.permissions');
        foreach ($permissions as $action => $roles) {
            Gate::define(
                $action,
                function (User $user) use ($roles) {
                    return in_array($user->role, $roles);
                }
            );
        }
        */

        // from tables/database
        // $permissions = DB::table('role_permission', 'rp')
        //                 ->select('p.action', DB::raw('GROUP_CONCAT(rp.role_id SEPARATOR \',\') AS roles'))
        //                 ->join('roles as r', 'r.id', '=', 'rp.role_id')
        //                 ->join('permissions as p', 'p.id', '=', 'rp.permission_id')
        //                 ->groupBy('p.action')
        //                 ->get();
        
        // foreach ($permissions as $p) {
        //     $roles = explode(',', $p->roles);
        //     Gate::define(
        //         $p->action,
        //         function (User $user) use ($roles) {
        //             return in_array($user->role, $roles);
        //         }
        //     );
        // }

        $permissions = DB::table('user_permission', 'up')
                        ->select('p.action', DB::raw('GROUP_CONCAT(up.user_id SEPARATOR \',\') AS user_ids'))
                        ->join('permissions as p', 'p.id', '=', 'up.permission_id')
                        ->groupBy('p.action')
                        ->get();
        
        foreach ($permissions as $p) {
            $users = explode(',', $p->user_ids);
            Gate::define(
                $p->action,
                function (User $user) use ($users) {
                    return in_array($user->id, $users);
                }
            );
        }
    }
}
