<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-external-link-alt text-primary"></i>
                </span>
                <span class="">Your Favorite Websites</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Websites List</h3>
                </div>
                <div class="block-content">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> {!! session('success') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> {!! session('error') !!}</p>
                        </div>
                    @endif
                    <div class="table-responsive push text-right">
                        <button class="btn btn-dark" id="add-link">
                            <i class="fa fa-plus"></i> Add Link
                        </button>
                        <button class="btn btn-success" id="add-bulk-links">
                            <i class="fa fa-upload"></i> Add Bulk Links
                        </button>
                        <button class="btn btn-danger" id="remove-all-links">
                            <i class="fa fa-trash"></i> Remove All Links
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-vcenter" id="links-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 80px;">Link #</th>
                                    <th class="text-center" style="width: 20%;">Name</th>
                                    <th class="text-center" style="width: 30%;">Url</th>
                                    <th class="d-none d-sm-table-cell text-center">Description</th>
                                    <th class="d-none d-md-table-cell text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($ext_links) && count($ext_links) > 0)
                            @foreach ($ext_links as $link)
                                <tr>
                                    <td class="font-w600 font-size-sm text-center">{{ $link->id }}</td>
                                    <td class="font-w600 font-size-sm text-center">
                                        <a href="{{ $link->url }}" target="_blank" class="text-warning"><strong>{{ $link->name }}</strong></a>
                                    </td>
                                    <td class="font-w600 font-size-sm text-left">{{ $link->url }}</td>
                                    <td class="d-none d-sm-table-cell font-size-sm text-left text-success">{{ $link->description }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-dark view-link" title="View Link" data-id="{{ $link->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-link" title="Edit Link" data-id="{{ $link->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-link" title="Remove Link" data-id="{{ $link->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="font-w600 font-size-sm text-center">No External Links</td>
                                </tr>
                            @endif    
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Link Info: Pop Out Block Modal -->
                <div class="modal fade" id="modal-link-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Website Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-dark table-vcenter" id="link-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w800 text-right" style="width: 30%;">Link # : </td>
                                                    <td class="text-left" id="t_id"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Name : </td>
                                                    <td class="text-left text-primary" id="t_name"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">URL : </td>
                                                    <td class="text-left" id="t_url"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w800 text-right">Description : </td>
                                                    <td class="text-left" id="t_description"></td>
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
                <!-- Upload Form: Pop Out Block Modal -->
                <div class="modal fade" id="modal-upload-form" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Upload Form</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="row push">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 alert alert-info alert-dismissable" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> While uploading data, old data will be truncated !</p>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <p class="font-size-sm text-muted text-right">
                                                Bulk Links File:
                                            </p>
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12 overflow-hidden">
                                            <form action="/util/ext-links/upload" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="file" id="upload-file" name="upload-file" accept=".xlsx,.xls,.csv">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-upload text-mute"></i> Upload
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function inital_modal() {
            $('#link-table').removeClass('d-none');
            $('#modal-link-info .table-responsive .alert').remove();

            $('#t_id').html('');
            $('#t_name').html('');
            $('#t_url').html('');
            $('#t_descrition').html('');
        }
        $('button.view-link').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/util/ext-links/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    if (data.type == 'success') {
                        inital_modal();
                        
                        $('#t_id').html(data.link.id);
                        $('#t_name').html('<a href="' + data.link.url + '" target="_blank">' + data.link.name + '</a>');
                        $('#t_url').html(data.link.url);
                        $('#t_description').html(data.link.description);
                        
                        $('#modal-link-info').modal('show');
                    } else {
                        inital_modal();
                        
                        $('#link-table').addClass('d-none');
                        $('#modal-link-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                        
                        $('#modal-link-info').modal('show');
                    }
				}
            });
        });
        $('button.edit-link').click(function() {
            var id = $(this).data('id');
            location.href = '/util/ext-links/edit/' + id;
        });
        $('button.remove-link').click(function() {
            var id = $(this).data('id');
            location.href = '/util/ext-links/remove/' + id;
        });
        $('button#add-link').click(function() {
            location.href = '/util/ext-links/add';
        });
        $('button#remove-all-links').click(function() {
            location.href = '/util/ext-links/truncate';
        });
        $('button#add-bulk-links').click(function() {
            $('#modal-upload-form').modal('show');
        });
    });
});
</script>
</x-app-layout>