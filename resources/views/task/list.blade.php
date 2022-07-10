<x-app-layout>
<style type="text/css">
    .badge {
        font-size: 100%;
    }
    .badge-violet {
        color: #fff;
        background-color: #7F00FF;
    }
    @media (min-width: 768px) {
        .modal-dialog {
            max-width: 700px;
            margin: 1.75rem auto;
        }
    }
</style>
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-tasks text-primary"></i>
                </span>
                <span class="">My Tasks</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Task List</h3>
                </div>
                <div class="block-content">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0"><i class="fa fa-fw fa-info-circle"></i> {!! session('success') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0"><i class="fa fa-fw fa-info-circle"></i> {!! session('error') !!}</p>
                        </div>
                    @endif
                    <form action="/task/list" method="POST" autocomplete="off" id="search-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="user-name">Task Name :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="task-name" 
                                        id="task-name" 
                                        value="{{ $task_name }}"
                                        placeholder="Enter Task Name.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="user-email">Recurring :</label>
                                    <select name="task-recurring" id="task-recurring" class="form-control">
                                        <option value=""></option>
                                        <option value="No" @if($task_recurring == "No"){{ 'selected' }}@endif>No</option>
                                        <option value="Yes" @if($task_recurring == "Yes"){{ 'selected' }}@endif>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="task-status">Status :</label>
                                    <select name="task-status" id="task-status" class="form-control">
                                        <option value=""></option>
                                        <option value="pending" @if($task_status == "pending"){{ 'selected' }}@endif>Pending</option>
                                        <option value="in progress" @if($task_status == "in progress"){{ 'selected' }}@endif>In Progress</option>
                                        <option value="completed" @if($task_status == "completed"){{ 'selected' }}@endif>Completed</option>
                                        <option value="cancelled" @if($task_status == "cancelled"){{ 'selected' }}@endif>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="user-name">Owner :</label>
                                    <input type="text" 
                                        class="form-control" 
                                        name="task-owner" 
                                        id="task-owner" 
                                        value="{{ $task_owner }}"
                                        placeholder="Enter Owner Name.." 
                                    />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="search-user">&nbsp;</label>
                                    <button class="form-control btn btn-primary ml-auto mr-3" id="search-tasks" name="search-tasks">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="add-task">&nbsp;</label>
                                    <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="add-task" name="add-task">
                                        <i class="fa fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-dark table-vcenter" id="tasks-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40%;">Name</th>
                                    <!-- <th class="text-center">Recurring</th>
                                    <th class="text-center">Interval</th>
                                    <th class="d-none d-sm-table-cell text-center">From</th>
                                    <th class="d-none d-sm-table-cell text-center">To</th> -->
                                    <th class="d-none d-sm-table-cell text-center">Due Date</th>
                                    <th class="d-none d-sm-table-cell text-center" style="min-width: 115px;">Status</th>
                                    <th class="d-none d-sm-table-cell text-center">Owner</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($tasks) && count($tasks) > 0)
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="font-w600 font-size-sm text-left text-primary">{{ $task->name }}</td>
                                    <!-- <td class="font-w600 font-size-sm text-center">
                                        @if ($task->recurring == 'Yes')
                                        <span class="badge badge-success">{{ $task->recurring }}</span>
                                        @else
                                        {{ $task->recurring }}
                                        @endif
                                    </td>
                                    <td class="font-w600 font-size-sm text-right @if ($task->recurring == 'Yes'){{ 'text-success' }}@endif">
                                        @if ($task->interval > 0)
                                        {{ $task->interval . ' days' }}
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell font-w600 font-size-sm text-center">{{ $task->from_date }}</td>
                                    <td class="d-none d-sm-table-cell font-w600 font-size-sm text-center">{{ $task->to_date }}</td> -->
                                    <td class="d-none d-sm-table-cell font-w600 font-size-sm text-center">
                                        @if ($task->recurring == 'No')
                                            @if ($task->due_date < date('Y-m-d'))
                                               <span class="text-danger">{{ $task->due_date }}</span>
                                            @else
                                                {{ $task->due_date }}
                                            @endif
                                        @else
                                            @if ($task->to_date && $task->to_date < date('Y-m-d'))
                                                <span class="text-danger">{{ $task->from_date . ', ' . $task->interval . ' days' }}</span>
                                            @else
                                                {{ $task->from_date . ', ' . $task->interval . ' days' }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell font-w600 font-size-sm text-center">
                                        @if ($task->status == 'completed')
                                        <span class="badge badge-success">{{ $task->status }}</span>
                                        @elseif($task->status == 'cancelled')
                                        <span class="badge badge-danger">{{ $task->status }}</span>
                                        @elseif($task->status == 'in progress')
                                        <span class="badge badge-primary">{{ $task->status }}</span>
                                        @else
                                        {{ $task->status }}
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell font-w600 font-size-sm text-left">
                                        @if ($task->owner)
                                        {{ $task->owner->name }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-dark view-task" title="View Task" data-id="{{ $task->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-task" title="Edit Task" data-id="{{ $task->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-task" title="Remove Task" data-id="{{ $task->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                            
                                            <!-- <button type="button" class="btn btn-sm btn-dark change-status" title="Change Status" data-id="{{ $task->id }}">
                                                <i class="fa fa-fw fa-check"></i>
                                            </button> -->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="9">No Tasks</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pop Out Block Modal -->
                <div class="modal fade" id="modal-task-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Task Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-dark table-vcenter" id="task-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w800 text-right" style="width: 50%;">Name : </td>
                                                    <td class="text-left text-primary" id="t_name"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Recurring : </td>
                                                    <td class="text-left" id="t_recurring"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Interval : </td>
                                                    <td class="text-left" id="t_interval"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">From Date : </td>
                                                    <td class="text-left" id="t_from_date"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">To Date : </td>
                                                    <td class="text-left" id="t_to_date"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Due Date : </td>
                                                    <td class="text-left" id="t_due_date"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Status : </td>
                                                    <td class="text-left" id="t_status"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Created By : </td>
                                                    <td class="text-left" id="t_creator"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Owner : </td>
                                                    <td class="text-left" id="t_owner"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="block-content block-content-full text-right border-top">
                                    <button type="button" class="btn btn-sm btn-dark" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Pop Out Block Modal -->
                <!-- Pop Out Block Modal -->
                <div class="modal fade" id="modal-change-status" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Task Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-dark table-vcenter" id="change-status-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w800 text-right" style="width: 50%;">Name : </td>
                                                    <td class="text-left text-primary" id="t_name1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Recurring : </td>
                                                    <td class="text-left" id="t_recurring1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Interval : </td>
                                                    <td class="text-left" id="t_interval1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">From Date : </td>
                                                    <td class="text-left" id="t_from_date1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">To Date : </td>
                                                    <td class="text-left" id="t_to_date1"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Status : </td>
                                                    <td class="text-left" id="t_status1">
                                                        <div class="form-group mb-0">
                                                            <input type="hidden" name="t-id" id="t-id" value="" />
                                                            <select name="t-status" id="t-status" class="form-control">
                                                                <option value="pending">Pending</option>
                                                                <option value="in progress">In Progress</option>
                                                                <option value="completed">Completed</option>
                                                                <option value="cancelled">Cancelled</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Created By : </td>
                                                    <td class="text-left" id="t_creator1"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="block-content block-content-full text-right border-top">
                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="btn-change-status">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Pop Out Block Modal -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function inital_modal() {
            $('#task-table').removeClass('d-none');
            $('#modal-task-info .table-responsive .alert').remove();

            $('#change-status-table').removeClass('d-none');
            $('#modal-change-status .table-responsive .alert').remove();

            $('#t_name').html('');
            $('#t_recurring').html('');
            $('#t_interval').html('');
            $('#t_from_date').html('');
            $('#t_to_date').html('');
            $('#t_due_date').html('');
            $('#t_status').html('');
            $('#t_creator').html('');
            $('#t_owner').html('');

            $('#t_name1').html('');
            $('#t_recurring1').html('');
            $('#t_interval1').html('');
            $('#t_from_date1').html('');
            $('#t_to_date1').html('');
            $('#t_creator1').html('');
            $('#t-id').val('');
        }
        $('button.view-task').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/task/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    inital_modal();

                    if (data.type == 'success') {
                        $('#t_name').html(data.task.name);
                        if (data.task.recurring == "Yes") {
                            $('#t_recurring').html('<span class="badge badge-success">' + data.task.recurring + '</span>');
                        }
                        else {
                            $('#t_recurring').html(data.task.recurring);
                        }
                        $('#t_interval').html(data.task.interval + ' days');
                        $('#t_from_date').html(data.task.from_date);
                        $('#t_to_date').html(data.task.to_date);
                        $('#t_due_date').html(data.task.due_date);
                        $('#t_status').html(data.task.status);
                        $('#t_creator').html(data.task.creator.name);
                        $('#t_owner').html(data.task.owner ? data.task.owner.name : '');
                    } else {
                        $('#task-table').addClass('d-none');
                        $('#modal-task-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                        
                    }
                    $('#modal-task-info').modal('show');
				}
            });
        });
        $('button.change-status').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/task/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    inital_modal();

                    if (data.type == 'success') {
                        $('#t-id').val(data.task.id);
                        $('#t_name1').html(data.task.name);
                        if (data.task.recurring == "Yes") {
                            $('#t_recurring1').html('<span class="badge badge-success">' + data.task.recurring + '</span>');
                        }
                        else {
                            $('#t_recurring1').html(data.task.recurring);
                        }
                        $('#t_interval1').html(data.task.interval + ' days');
                        $('#t_from_date1').html(data.task.from_date);
                        $('#t_to_date1').html(data.task.to_date);
                        $('#t_creator1').html(data.task.creator.name);
                        $('#t-status').val(data.task.status);
                    } else {
                        $('#change-status-table').addClass('d-none');
                        $('#modal-change-status .table-responsive').prepend(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                        
                    }
                    $('#modal-change-status').modal('show');
				}
            });
        });
        $('button.edit-task').click(function() {
            var id = $(this).data('id');
            location.href = '/task/edit/' + id;
        });
        $('button.remove-task').click(function() {
            var id = $(this).data('id');
            location.href = '/task/remove/' + id;
        });
        $('button#add-task').click(function() {
            location.href = '/task/add';
        });
        $('button#btn-change-status').click(function() {
            var id = $("#t-id").val();
            var status = $("#t-status").val();
            $.ajax({
				url:        "/task/change-status",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id,
                                status: status
                            },
				success:    function( data ) {
                    $('#modal-change-status .table-responsive').prepend(
                        '<div class="alert alert-info alert-dismissable" role="alert">' + 
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                '<span aria-hidden="true">×</span>' +
                            '</button>' +
                            '<p class="mb-0">' + data.message + '</p>' +
                        '</div>');
                }
            });
        });
    });
});
</script>
</x-app-layout>