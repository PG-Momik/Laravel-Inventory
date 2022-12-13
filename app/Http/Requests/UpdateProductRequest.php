<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'          => 'required|string',
            'category'      => 'required|numeric',
            'quantity'      => 'required|numeric|min:0',
            'price'         => 'numeric|min:0',
            'purchasePrice' => 'numeric|min:0',
            'salesPrice'    => 'numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'description'   => 'nullable|string',
            'productImage'  => 'nullable|image',
        ];
    }
}
