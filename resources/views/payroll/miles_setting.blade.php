<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-file-csv text-primary"></i>
                </span>
                <span class="">Miles Setting For Fixed Rate</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row justify-content-lg-center">
        <div class="col-lg-8">
            <div class="block block-themed">
                <div class="block-header bg-primary-darker">
                    <h3 class="block-title">Edit Form</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizonal" action="/payroll/miles-setting/save" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="from-m-fr">From Miles For Fixed Rate <span class="text-danger">*</span> : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="from-m-fr" name="from-m-fr" value="{{ number_format($from_m_fr, 4) }}" min="0" step="0.0001" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">mi.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="to-m-fr">To Miles For Fixed Rate <span class="text-danger">*</span> : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="to-m-fr" name="to-m-fr" value="{{ number_format($to_m_fr, 4) }}" min="0" step="0.0001" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">mi.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="button" class="btn btn-light ml-auto mr-3" onclick="javascript:window.history.back(-1);">Cancel</button>
                            <button class="btn btn-dark mr-3">Update Setting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
