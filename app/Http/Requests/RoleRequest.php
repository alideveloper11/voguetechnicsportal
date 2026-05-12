<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name' => 'required|unique:roles',
            'permissions' => 'nullable|array',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $roleId = $this->route('role');
            $rules['name'] = 'required|unique:roles,name,' . $roleId;
        }

        return $rules;
    }
}
