<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-tasks text-primary"></i>
                </span>
                <span class="">Task</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-default-darker">
                    <h3 class="block-title">Task Info Form</h3>
                </div>
                <div class="block-content">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <form class="js-validation" action="/task/save" method="POST" id="task-form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="@if (isset($task)){{ $task->id }}@endif" />
                        <div class="table-responsive push text-right">
                            <button type="button" class="btn btn-dark view-tasks">
                                <i class="fa fa-list"></i> View Tasks
                            </button>
                            <button type="submit" class="btn btn-primary save-task">
                                <i class="fa fa-save"></i> Save Task
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="task-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Name<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" class="form-control" name="name" value="@if (isset($task)){{ $task->name }}@else{{ old('name') }}@endif" placeholder="Enter Name.." required />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Recurring<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <select name="recurring" id="recurring" class="form-control" required>
                                                <option value="No" @if (isset($task) && ($task->recurring == "No")) {{ __('selected') }} @elseif (old('recurring') == "No") {{ __('selected') }} @endif>No</option>    
                                                <option value="Yes" @if (isset($task) && ($task->recurring == "Yes")) {{ __('selected') }} @elseif (old('recurring') == "Yes") {{ __('selected') }} @endif>Yes</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="interval @if (!isset($task) || (isset($task) && $task->recurring == 'No')) {{ 'd-none' }} @endif">
                                        <td class="font-w800 text-right" style="width: 20%;">Interval (Days): </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="number" 
                                                class="form-control" 
                                                name="interval" 
                                                value="@if (isset($task)){{ $task->interval }}@else{{ old('interval') }}@endif" 
                                                min="0"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="from-date @if (!isset($task) || (isset($task) && $task->recurring == 'No')) {{ 'd-none' }} @endif">
                                        <td class="font-w800 text-right" style="width: 20%;">From Date : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" 
                                                class="form-control js-datepicker" 
                                                name="from-date" 
                                                value="@if (isset($task)){{ $task->from_date }}@else{{ old('from-date') }}@endif" 
                                                data-week-start="0" 
                                                data-autoclose="true" 
                                                data-today-highlight="true" 
                                                data-date-format="yyyy-mm-dd" 
                                                placeholder="yyyy-mm-dd"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="to-date @if (!isset($task) || (isset($task) && $task->recurring == 'No')) {{ 'd-none' }} @endif">
                                        <td class="font-w800 text-right" style="width: 20%;">To Date : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" 
                                                class="form-control js-datepicker" 
                                                name="to-date" 
                                                value="@if (isset($task)){{ $task->to_date }}@else{{ old('to-date') }}@endif" 
                                                data-week-start="0" 
                                                data-autoclose="true" 
                                                data-today-highlight="true" 
                                                data-date-format="yyyy-mm-dd" 
                                                placeholder="yyyy-mm-dd"
                                            />
                                        </td>
                                    </tr>
                                    <tr class="due-date @if (isset($task) && $task->recurring == 'Yes') {{ 'd-none' }} @endif">
                                        <td class="font-w800 text-right" style="width: 20%;">Due Date : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <input type="text" 
                                                class="form-control js-datepicker" 
                                                name="due-date" 
                                                value="@if (isset($task)){{ $task->due_date }}@else{{ old('due-date') }}@endif" 
                                                data-week-start="0" 
                                                data-autoclose="true" 
                                                data-today-highlight="true" 
                                                data-date-format="yyyy-mm-dd" 
                                                placeholder="yyyy-mm-dd"
                                            />
                                        </td>
                                    </tr>
                                    @if (isset($task))
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Status <span class="text-danger">*</span> : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="pending" @if($task->status == 'pending'){{ 'selected' }}@elseif(old('status') == 'pending'){{ 'selected' }}@endif>Pending</option>
                                                <option value="in progress" @if($task->status == 'in progress'){{ 'selected' }}@elseif(old('status') == 'in progress'){{ 'selected' }}@endif>In Progress</option>
                                                <option value="completed" @if($task->status == 'completed'){{ 'selected' }}@elseif(old('status') == 'completed'){{ 'selected' }}@endif>Completed</option>
                                                <option value="cancelled" @if($task->status == 'cancelled'){{ 'selected' }}@elseif(old('status') == 'cancelled'){{ 'selected' }}@endif>Cancelled</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Owner : </td>
                                        <td class="text-left form-group" style="width: 30%;">
                                            <select name="owner" id="owner" class="form-control">
                                                <option value=""></option>
                                                @foreach ($o_users as $user)
                                                <option value="{{ $user['id'] }}" @if(isset($task) && $task->owner_id == $user['id']){{ 'selected' }}@elseif(old('owner') == $user['id']){{ 'selected' }}@endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right align-top" style="width: 20%;">Users for sharing : </td>
                                        <td class="text-left align-top" style="width: 30%;">
                                            @foreach ($users as $user)
                                                @php
                                                    $flag = false
                                                @endphp
                                                @foreach ($shared_users as $su)
                                                    @if ($user->id == $su->id)
                                                        @php
                                                            $flag = true
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="users[]" id="su-{{ $user->id }}" value="{{ $user->id }}" @if($flag) {{ 'checked' }}@endif/>
                                                    <label class="form-check-label" for="su-{{ $user->id }}">{{ $user->name }}</label>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    One.helpers(['datepicker']);

    $(document).ready(function() {
        $('button.view-tasks').click(function() {
            location.href = "/task/list";
        });

        $('select#recurring').change(function() {
            var recurring = $(this).val();

            if (recurring === 'Yes') {
                $('tr.interval').removeClass('d-none');
                $('tr.from-date').removeClass('d-none');
                $('tr.to-date').removeClass('d-none');
                $('tr.due-date').addClass('d-none');
            } else {
                $('tr.interval').addClass('d-none');
                $('tr.from-date').addClass('d-none');
                $('tr.to-date').addClass('d-none');
                $('tr.due-date').removeClass('d-none');
            }
        });
    });
});
</script>
</x-app-layout>