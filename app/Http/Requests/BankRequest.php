<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'website_id' => 'required|exists:websites,id',
            'name' => 'required|string|max:255',
            'account_title' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:banks,account_number',
            'branch_name' => 'nullable|string|max:255',
            'sort_code' => 'required|string|max:255',
            'is_vat' => 'nullable|boolean',
            'vat' => 'nullable|required_if:is_vat,1|numeric|min:0|max:100',
            'status' => 'nullable|boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $bank = $this->route('bank');
            $rules['account_number'] = 'required|string|max:255|unique:banks,account_number,' . $bank->id;
        }

        return $rules;
    }
}
