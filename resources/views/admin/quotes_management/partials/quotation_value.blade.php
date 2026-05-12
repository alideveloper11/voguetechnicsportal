<div class="card mt-4">
    <h5 class="card-header">Quotation Values</h5>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="email_template_id">Email Template</label>
                <select id="email_template_id" name="email_template_id" class="select2 form-select" data-placeholder="Select Email Template">
                    <option value="">Select Email Template</option>
                    @foreach ($emailTemplates as $template)
                        <option value="{{ $template->id }}"
                            {{ old('email_template_id', $quote->email_template_id ?? '') == $template->id ? 'selected' : '' }}>
                            {{ $template->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="mileage">Mileage </label>
                <input type="text" id="mileage" name="mileage" class="form-control" placeholder="Mileage"
                    value="{{ old('mileage', $quote->mileage ?? '') }}" />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="quote_amount">Amount </label>
                <input type="text" id="quote_amount" name="quote_amount" class="form-control" placeholder="Amount"
                    value="{{ old('quote_amount', $quote->quote_amount ?? '') }}" />
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="guarantee">Guarantee </label>
                <select id="guarantee" name="guarantee" class="select2 form-select" data-placeholder="Select Guarantee">
                    <option value="">Select Guarantee</option>
                    <option value="one_month"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'one_month' ? 'selected' : '' }}>
                        One Month</option>
                    <option value="two_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'two_months' ? 'selected' : '' }}>
                        Two Months
                    </option>
                    <option value="three_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'three_months' ? 'selected' : '' }}>
                        Three Months
                    </option>
                    <option value="four_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'four_months' ? 'selected' : '' }}>
                        Four Months
                    </option>
                    <option value="five_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'five_months' ? 'selected' : '' }}>
                        Five Months
                    </option>
                    <option value="six_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'six_months' ? 'selected' : '' }}>
                        Six Months
                    </option>
                    <option value="seven_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'seven_months' ? 'selected' : '' }}>
                        Seven Months
                    </option>
                    <option value="eight_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'eight_months' ? 'selected' : '' }}>
                        Eight Months
                    </option>
                    <option value="nine_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'nine_months' ? 'selected' : '' }}>
                        Nine Months
                    </option>
                    <option value="ten_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'ten_months' ? 'selected' : '' }}>
                        Ten Months
                    </option>
                    <option value="eleven_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'eleven_months' ? 'selected' : '' }}>
                        Eleven Months
                    </option>
                    <option value="twelve_months"
                        {{ old('guarantee', $quote->guarantee ?? '') == 'twelve_months' ? 'selected' : '' }}>
                        Twelve Months
                    </option>
                </select>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="delivery_time">Delivery Time </label>
                <select id="delivery_time" name="delivery_time" class="select2 form-select"
                    data-placeholder="Select Delivery Time">
                    <option value="">Select Delivery Time</option>
                    <option value="next_day"
                        {{ old('delivery_time', $quote->delivery_time ?? '') == 'next_day' ? 'selected' : '' }}>
                        Next Day</option>
                    <option value="2_to_3_days"
                        {{ old('delivery_time', $quote->delivery_time ?? '') == '2_to_3_days' ? 'selected' : '' }}>
                        2 to 3 Days</option>
                    <option value="upto_7_days"
                        {{ old('delivery_time', $quote->delivery_time ?? '') == 'upto_7_days' ? 'selected' : '' }}>
                        Up to 7 Days</option>
                    <option value="upto_14_days"
                        {{ old('delivery_time', $quote->delivery_time ?? '') == 'upto_14_days' ? 'selected' : '' }}>
                        Up to 14 Days
                    </option>
                </select>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label" for="offer_type">Offer Type </label>
                <select id="offer_type" name="offer_type" class="select2 form-select"
                    data-placeholder="Select Offer Type">
                    <option value="">Select Offer Type</option>
                    <option value="new"
                        {{ old('offer_type', $quote->offer_type ?? '') == 'new' ? 'selected' : '' }}>
                        New</option>
                    <option value="second_hand"
                        {{ old('offer_type', $quote->offer_type ?? '') == 'second_hand' ? 'selected' : '' }}>
                        Second Hand
                    </option>
                    <option value="reconditioned"
                        {{ old('offer_type', $quote->offer_type ?? '') == 'reconditioned' ? 'selected' : '' }}>
                        Reconditioned
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>
