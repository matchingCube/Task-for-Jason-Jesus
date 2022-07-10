<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-user text-primary"></i>
                </span>
                <span class="">Driver Information</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title">Drivers List</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter table-dark">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">
                            <i class="far fa-user"></i>
                        </th>
                        <th class="text-left">Name</th>
                        <th class="d-none d-lg-table-cell text-center">Email</th>
                        <th class="text-center">FedEX ID</th>
                        <th class="d-none d-md-table-cell text-center">Start Date</th>
                        <th class="d-none d-md-table-cell text-center">Birth Date</th>
                        <th class="d-none d-md-table-cell text-center">MEC</th>
                        <th class="d-none d-md-table-cell text-center">MVR</th>
                        <th class="d-none d-md-table-cell text-center">COV</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if (isset($persons) && count($persons) > 0)
                @foreach ($persons as $person)
                    <tr>
                        <td class="text-center">
                            <img class="img-avatar img-avatar48" src="{{ asset('/media/photos/drivers/' . $person->photo) }}" alt="">
                        </td>
                        <td class="font-w600 font-size-sm">
                            <a href="/drivers/scorecards/{{ $person->id }}">{{ $person->name }}</a>
                        </td>
                        <td class="d-none d-lg-table-cell font-w600 font-size-sm">{{ $person->email }}</td>
                        <td class="text-center">
                            <span class="badge badge-success">{{ $person->fedex_id }}</span>
                        </td>
                        <td class="d-none d-md-table-cell font-size-sm text-center">{{ $person->drug_test }}</td>
                        <td class="d-none d-md-table-cell font-size-sm text-center">{{ $person->birth }}</td>
                        <td class="d-none d-md-table-cell font-size-sm text-center {{ $person->mec_color }}">{{ $person->mec }}</td>
                        <td class="d-none d-md-table-cell font-size-sm text-center {{ $person->mvr_color }}">{{ $person->mvr }}</td>
                        <td class="d-none d-md-table-cell font-size-sm text-center {{ $person->cov_color }}">{{ $person->cov }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary view-scorecard" data-toggle="tooltip" title="View Scorecard" data-id="{{ $person->id }}">
                                    <i class="fa fa-fw fa-id-card"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary send-email" data-toggle="tooltip" title="Send Email" data-id="{{ $person->id }}" data-email="{{ $person->email }}">
                                    <i class="fa fa-fw fa-inbox"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary remove-person" data-toggle="tooltip" title="Remove Person" data-id="{{ $person->id }}" data-name="{{ $person->name }}">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="10">No Drivers</td>
                </tr>
                @endif    
                </tbody>
            </table>
            </div>
            
        </div>
    </div>        
</div>
<script src="{{ mix('js/check-st.js') }}" ></script>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $('button.view-scorecard').click(function() {
            location.href = "/drivers/scorecards/" + $(this).data('id');
        });
        $('button.send-email').click(function() {
            let id = $(this).data('id');
            let email = $(this).data('email');
            $.ajax({
				url:        "/scorecards/send-email",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                person_id: id,
                                email: email
                            },
				success:    function( data ) {
                    if (data.type == 'success') {
                        alert("Mail has been sent successfully!");
                    } else {
                        alert("Error Sent Mail!");
                    }
				}
            });
        });
        $('button.remove-person').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            Swal.fire({
                    title: 'Are you sure?',
                    html: "Driver <strong>" + name + "</strong>'s data with photo and scorecard will be removed! <br> You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = "/scorecards/person/remove/" + id;
                    }
            })
        });
    });
});
</script>
</x-app-layout>
