<tr>
    <td class="text-center">
        <div class="input-group">
            <input type="number" 
                class="form-control text-right"
                name="from-miles[]"
                value="@if(isset($rate)){{ $rate->from_miles }}@else{{ 0.0000 }}@endif"
                min="0"
                step="0.0001"
                required
            />
            <div class="input-group-append">
                <span class="input-group-text">mi.</span>
            </div>
        </div>
    </td>
    <td class="text-center">
        <div class="input-group">
            <input type="number" 
                class="form-control text-right"
                name="to-miles[]"
                value="@if(isset($rate)){{ $rate->to_miles }}@else{{ 0.0000 }}@endif"
                min="0"
                step="0.0001"
                required
            />
            <div class="input-group-append">
                <span class="input-group-text">mi.</span>
            </div>
        </div>
    </td>
    <td class="text-center">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">$</span>
            </div>
            <input type="number" 
                class="form-control text-right"
                name="fixed-rate[]"
                value="@if(isset($rate)){{ $rate->fixed_rate }}@else{{ 0.0000 }}@endif"
                min="0"
                step="0.0001"
                required
            />
        </div>
    </td>
    
    <td class="text-center">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-default remove-fixed-rate" title="Remove Fixed Rate">
                <i class="fa fa-fw fa-trash"></i>
            </button>
        </div>
    </td>
</tr>