<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_profile_image' => 'nullable|boolean',
            'password' => 'required|min:8|max:12|confirmed',
            'status' => 'required|in:active,inactive,pending',
            'is_mechanic' => 'nullable|boolean',
            'role' => 'required|exists:roles,id',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $user = $this->route()->parameter('user');
            $rules['password'] = 'nullable|min:8|max:12|confirmed';
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        return $rules;
    }
}
