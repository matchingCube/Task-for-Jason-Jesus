<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Task;
use App\Models\UserTask;
use App\Models\User;

class TaskController extends Controller
{
    protected function getQueryForTasksByUser($user_id, $filters)
    {
        $query = UserTask::with('task')
                        ->whereHas('task', function($q) use ($filters) {
                            if (isset($filters['name'])) {
                                $q->where('name', 'like', '%' . $filters['name'] . '%');
                            }
                            if (isset($filters['recurring'])) {
                                $q->where('recurring', '=', $filters['recurring']);
                            }
                            if (isset($filters['status'])) {
                                $q->where('status', '=', $filters['status']);
                            }
                            if (isset($filters['owner'])) {
                                $q->whereHas('owner', function($q1) use ($filters) {
                                    $q1->where('name', 'like', '%' . $filters['owner'] . '%');
                                });
                            }
                        })
                        ->where('user_id', '=', $user_id)
                        ->orderBy('task_id', 'DESC')
                        ->get();
        return $query;
    }

    protected function getUsersByCompany($user)
    {
        $company_id = $user->company_id;
        $user_id = $user->id;

        if ($user->role == 1) {
            return User::where('id', '!=', $user_id)->get();
        }

        return User::ofCompany($company_id)
                    // ->where('role', '!=', '1')
                    // ->where('id', '!=', $user_id)
                    ->orWhere('role', '1')
                    ->get();
    }

    protected function getSharedUsersByTask($task)
    {
        $task_id = $task->id;
        $user_id = $task->user_id;

        $shared_users = DB::table('user_task as ut')
                    ->select('u.*')
                    ->join('users as u', 'u.id', '=', 'ut.user_id')
                    ->where('ut.task_id', '=', $task_id)
                    ->where('ut.user_id', '!=', $user_id)
                    ->get();
        return $shared_users;
    }

    public function list(Request $request)
    {
        // $this->authorize('manage-task');

        $user = Auth::user();
        
        $filters = [];
        
        if ($request->has('task-name')) {
            $task_name = $request->input('task-name');
            $filters['name'] = $task_name;
        } else {
            $task_name = "";
        }

        if ($request->has('task-recurring') && !empty($request->input('task-recurring'))) {
            $task_recurring = $request->input('task-recurring');
            $filters['recurring'] = $task_recurring;
        } else {
            $task_recurring = "";
        }

        if ($request->has('task-status') && !empty($request->input('task-status'))) {
            $task_status = $request->input('task-status');
            $filters['status'] = $task_status;
        } else {
            $task_status = "";
        }

        if ($request->has('task-owner') && !empty($request->input('task-owner'))) {
            $task_owner = $request->input('task-owner');
            $filters['owner'] = $task_owner;
        } else {
            $task_owner = "";
        }

        $list = $this->getQueryForTasksByUser($user->id, $filters);
        
        $tasks = [];
        foreach ($list as $t) {
            $tasks[] = $t->task;
        }

        return view('task.list', compact('tasks', 'task_name', 'task_recurring', 'task_status', 'task_owner'));
    }

    public function getTask(Request $request)
    {
        // $this->authorize('manage-task');

        $id = $request->input('id');
        $task = Task::with('creator')->with('owner')->find($id);

        return response()->json([
            'type' => 'success',
            'task' => $task
        ]);
    }

    public function getAddPage(Request $request)
    {
        // $this->authorize('manage-task');
        
        $user = Auth::user();
        $users = $this->getUsersByCompany($user);
        $shared_users = [];

        $o_users = [];
        foreach ($users as $u) {
            $o_users[] = ['id' => $u->id , 'name' => $u->name];
        }
        $o_users = array_unique($o_users, SORT_REGULAR);
                
        return view('task.task', compact('users', 'shared_users', 'o_users'));
    }

    public function editTask(Request $request)
    {
        // $this->authorize('manage-task');

        $id = $request->route()->parameter('id');

        $task = Task::with('creator')->with('owner')->find($id);

        $user = Auth::user();
        $users = $this->getUsersByCompany($user);
        $o_users = [];
        foreach ($users as $u) {
            $o_users[] = ['id' => $u->id , 'name' => $u->name];
        }
        if ($task->owner) {
            $o_users[] = ['id' => $task->owner->id, 'name' => $task->owner->name];
        }
        $o_users = array_unique($o_users, SORT_REGULAR);
                
        $shared_users = $this->getSharedUsersByTask($task);

        return view('task.task', compact('task', 'users', 'shared_users', 'o_users'));
    }

    public function saveTask(Request $request)
    {
        // $this->authorize('manage-task');

        if (!$request->has('name') || !$request->has('recurring')) {
            $request->session()->flash('error', "Sorry, your input not validation! Please check your input.");
            return back()->withInput();
        }

        $id = $request->input('id');
        $name = $request->input('name');
        $recurring = $request->input('recurring');
        if ($recurring == 'Yes') {
            $interval = $request->input('interval') ?? 0;
            $from_date = $request->input('from-date') ?? null;
            $to_date = $request->input('to-date') ?? null;
            $due_date = null;
        } else {
            $interval = 0;
            $from_date = null;
            $to_date = null;
            $due_date = $request->input('due-date') ?? null;
        }
        
        $status = $request->input('status') ?? 'pending';
        $owner = $request->input('owner') ?? null;
        $users = $request->input('users') ?? [];
        
        if ($id) {
            $task = Task::find($id);
            $task->name = $name;
            $task->recurring = $recurring;
            $task->interval = $interval;
            $task->from_date = $from_date;
            $task->to_date = $to_date;
            $task->due_date = $due_date;
            $task->status = $status;
            $task->owner_id = $owner;
            
            $task->save();

            //update user_task table
            $bulks = [];
            foreach ($users as $user) {
                $bulks[] = [
                    'user_id' => $user,
                    'task_id' => $task->id
                ];
            }
            if (!empty($owner)) {
                $bulks[] = [
                    'user_id' => $owner,
                    'task_id' => $task->id
                ];
            }
            $bulks[] = [
                'user_id' => Auth::user()->id,
                'task_id' => $task->id
            ];
            $bulks[] = [
                'user_id' => $task->user_id,
                'task_id' => $task->id
            ];
            $bulks = array_unique($bulks, SORT_REGULAR);
            
            UserTask::where('task_id', '=', $task->id)->delete();
            UserTask::insert($bulks);

            $request->session()->flash('success', "Task was updated successfull!");
        } else {
            $task = Task::create([
                'name' => $name,
                'recurring' => $recurring,
                'interval' => $interval,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'due_date' => $due_date,
                'status' => $status,
                'user_id' => Auth::user()->id,
                'owner_id' => $owner
            ]);

            //update user_task table
            $bulks = [];
            foreach ($users as $user) {
                $bulks[] = [
                    'user_id' => $user,
                    'task_id' => $task->id
                ];
            }
            if (!empty($owner)) {
                $bulks[] = [
                    'user_id' => $owner,
                    'task_id' => $task->id
                ];
            }
            $bulks[] = [
                'user_id' => Auth::user()->id,
                'task_id' => $task->id
            ];
            $bulks[] = [
                'user_id' => $task->user_id,
                'task_id' => $task->id
            ];
            $bulks = array_unique($bulks, SORT_REGULAR);

            UserTask::where('task_id', '=', $task->id)->delete();
            UserTask::insert($bulks);
    
            $request->session()->flash('success', "New task was created for '" . $name . "'");
        }
        
        return $this->list($request);
    }

    public function removeTask(Request $request)
    {
        // $this->authorize('manage-task');

        $id = $request->route()->parameter('id');

        $res = Task::find($id)->delete();

        // delete user tasks
        delete_tasks_by_task($id);
        
        if ($res) {
            $request->session()->flash('success', 'Task removed successfully. (ID: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this task at this time. (ID: ' . $id . ') Please retry later.');
        }
        return $this->list($request);
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $task = Task::find($id);
        $task->status = $status;
        $task->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Status was updated successfully! Please refresh.'
        ]);
    }
}
