<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item"><h3 class="font-w700 mb-0">Payroll</h3></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx text-primary font-w700 h3" href="">Edit Rate</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row justify-content-lg-center">
        <div class="col-lg-8">
            <div class="block block-themed">
                <div class="block-header bg-primary-darker">
                    <h3 class="block-title">Edit Form</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizonal" action="/payroll/rate/save" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="{{ $rate->id }}" />
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="driver-id">Driver ID : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="driver-id" name="driver-id" value="{{ $rate->driver_id }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="driver-name">Driver Name : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="driver-name" name="driver-name" value="{{ $rate->driver_name }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="price-per-mile">Price Per Mile <span class="text-danger">*</span> : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control text-center" id="price-per-mile" name="price-per-mile" value="{{ number_format($rate->price_per_mile, 2) }}" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="button" class="btn btn-light ml-auto mr-3" id="cancel-rate" name="cancel-rate" onclick="javascript:window.history.back(-1);">Cancel</button>
                            <button class="btn btn-dark mr-3" id="update-rate" name="update-rate">Update Rate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
