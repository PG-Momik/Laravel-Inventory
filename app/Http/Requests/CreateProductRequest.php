<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string',
            'category'      => 'required|numeric',
            'quantity'      => 'required|numeric|min:0',
            'purchasePrice' => 'required|numeric|min:0',
            'salesPrice'    => 'required|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'description'   => 'nullable|string',
            'productImage'  => 'nullable|image',
        ];
    }
}
