<?php

namespace App\Http\Requests;

use App\Models\Website;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class WebsiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('name')) {
            return;
        }

        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug;
        $count = 1;
        $website = $this->route('website');

        while (
            Website::when($website, function ($query) use ($website) {
                $query->where('id', '!=', $website->id);
            })->where('slug', $slug)->exists()
        ) {
            $slug = $baseSlug . '-' . $count++;
        }

        $this->merge([
            'slug' => $slug,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // 'code' => 'required|string|max:255|unique:websites,code',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:websites,slug',
            'url' => 'required|string|max:255|unique:websites,url',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'landline' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'status' => 'nullable|boolean',
            'remove_logo' => 'nullable|boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $website = $this->route()->parameter('website');
            // $rules['code'] = 'required|string|max:255|unique:websites,code,' . $website->id;
            $rules['slug'] = 'required|string|max:255|unique:websites,slug,' . $website->id;
            $rules['url'] = 'required|string|max:255|unique:websites,url,' . $website->id;
            $rules['logo'] = 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048';
        }

        return $rules;
    }
}
