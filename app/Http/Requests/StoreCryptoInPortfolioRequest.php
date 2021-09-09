<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCryptoInPortfolioRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cg_id' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001|max:10000000'
        ];
    }
}
