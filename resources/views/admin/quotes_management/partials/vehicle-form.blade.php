@php
    $vehicle = $quote->vehicle ?? null;
@endphp
<div class="card mt-4">
    <h5 class="card-header">Vehicle Information</h5>
    <div class="card-body">
        <input type="hidden" id="vrm" name="vrm" value="{{ old('vrm', $vehicle->vrm ?? '') }}">
        <div class="row mb-3">
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="make">Vehicle Make <span class="text-danger">*</span></label>
                <input type="text" id="make" name="make" class="form-control" placeholder="Make"
                    value="{{ old('make', $vehicle->make ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="model">Vehicle Model <span class="text-danger">*</span></label>
                <input type="text" id="model" name="model" class="form-control" placeholder="Model"
                    value="{{ old('model', $vehicle->model ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="year">Year <span class="text-danger">*</span></label>
                <input type="text" id="year" name="year" class="form-control" placeholder="Year"
                    value="{{ old('year', $vehicle->year ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="fuel_type">Fuel Type <span class="text-danger">*</span></label>
                <input type="text" id="fuel_type" name="fuel_type" class="form-control" placeholder="Fuel Type"
                    value="{{ old('fuel_type', $vehicle->fuel_type ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="engine_size">Engine Size <span class="text-danger">*</span></label>
                <input type="text" id="engine_size" name="engine_size" class="form-control" placeholder="Engine Size"
                    value="{{ old('engine_size', $vehicle->engine_size ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="engine_code">Engine Code <span class="text-danger">*</span></label>
                <input type="text" id="engine_code" name="engine_code" class="form-control" placeholder="Engine Code"
                    value="{{ old('engine_code', $vehicle->engine_code ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="body_type">Body Type <span class="text-danger">*</span></label>
                <input type="text" id="body_type" name="body_type" class="form-control" placeholder="Body Type"
                    value="{{ old('body_type', $vehicle->body_type ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="engine_type">Engine Type</label>
                <input type="text" id="engine_type" name="engine_type" class="form-control" placeholder="Engine Type"
                    value="{{ old('engine_type', $vehicle->engine_type ?? '') }}"/>
            </div>

            <input type="hidden" id="maximum_bhp" name="maximum_bhp" class="form-control" placeholder="Maximum BHP"
                    value="{{ old('maximum_bhp', $vehicle->maximum_bhp ?? '') }}"/>
            <input type="hidden" id="engine_number" name="engine_number"
                value="{{ old('engine_number', $vehicle->engine_number ?? '') }}">
            <input type="hidden" id="vin" name="vin" value="{{ old('vin', $vehicle->vin ?? '') }}">
            <input type="hidden" id="color" name="color" value="{{ old('color', $vehicle->color ?? '') }}">
            <input type="hidden" id="number_of_doors" name="number_of_doors"
                value="{{ old('number_of_doors', $vehicle->number_of_doors ?? '') }}">
            <input type="hidden" id="seat_capacity" name="seat_capacity"
                value="{{ old('seat_capacity', $vehicle->seat_capacity ?? '') }}">
            <input type="hidden" id="wheel_plan" name="wheel_plan"
                value="{{ old('wheel_plan', $vehicle->wheel_plan ?? '') }}">
            <input type="hidden" id="aspiration" name="aspiration"
                value="{{ old('aspiration', $vehicle->aspiration ?? '') }}">
            <input type="hidden" id="transmission" name="transmission"
                value="{{ old('transmission', $vehicle->transmission ?? '') }}">
            <input type="hidden" id="co2_emissions" name="co2_emissions"
                value="{{ old('co2_emissions', $vehicle->co2_emissions ?? '') }}">
        </div>
    </div>
</div>
