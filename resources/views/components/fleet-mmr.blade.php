<tr>
    <td class="text-center">
        <select name="maintenance[]" class="form-control">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </td>
    <td class="text-center">
        <select name="out-of-service[]" class="form-control">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </td>
    <td class="text-center">
        @if (isset($fleets))
        <select name="tractor-id[]" class="form-control">
            @foreach ($fleets as $fleet)
            <option value="{{ $fleet->tractor_id }}">{{ $fleet->tractor_id }}</option>
            @endforeach
        </select>
        @endif
    </td>
    <td class="text-center">
        <input type="number"
                class="form-control text-right px-0"
                name="current-mileage[]"
                value="0.0000"
                placeholder=""
                min="0"
                step="0.0001"
                required
        />
    </td>
    <td class="text-center">
        <input type="text"
                class="date form-control text-center px-0"
                name="maintenance-date[]"
                value=""
                placeholder="mm-dd-yy"
        />
    </td>
    <td class="text-center">
        <input type="text"
                class="form-control"
                name="maintenance-desc[]"
                placeholder="Description"
                value=""
        />
    </td>
    <td class="text-center">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-default remove-maintenance" title="Remove Maintenance">
                <i class="fa fa-fw fa-trash"></i>
            </button>
        </div>
    </td>
</tr>