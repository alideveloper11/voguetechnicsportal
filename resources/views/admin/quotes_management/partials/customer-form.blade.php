@php
    $customer = $quote->customer ?? null;
@endphp
<div class="card">
    <h5 class="card-header">Customer Information</h5>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="name">Customer Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Name"
                    value="{{ old('name', $customer->name ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="email">Customer Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email"
                    value="{{ old('email', $customer->email ?? '') }}" required />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="phone">Customer Phone <span class="text-danger">*</span></label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone"
                    value="{{ old('phone', $customer->phone ?? '') }}" required />
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="customer_type_id">Customer Type <span class="text-danger">*</span></label>
                <select id="customer_type_id" name="customer_type_id" class="select2 form-select" data-placeholder="Select Customer Type" required>
                    <option value="">Select Customer Type</option>
                    @foreach ($customerTypes as $customer_type)
                        <option value="{{ $customer_type->id }}"
                            {{ old('customer_type_id', $customer->customer_type_id ?? '') == $customer_type->id ? 'selected' : '' }}>
                            {{ $customer_type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="city">City / Postcode <span class="text-danger">*</span></label>
                <input type="text" id="city" name="city" class="form-control" placeholder="City"
                    value="{{ old('city', $customer->city ?? '') }}" required />
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                <textarea name="address" id="address" rows="2" class="form-control" placeholder="Address" required>{{ old('address', $customer->address ?? '') }}</textarea>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label" for="issue">Customer Described Issues <span
                        class="text-danger">*</span></label>
                <textarea name="issue" id="issue" rows="2" class="form-control" placeholder="Issue" required>{{ old('issue', $quote->notes ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>
