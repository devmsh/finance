<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from_amount' => 'required|numeric',
            'from_type' => 'required',
            'from_id' => 'required|numeric',
            'to_amount' => 'required|numeric',
            'to_type' => 'required',
            'to_id' => 'required|numeric',
        ];
    }
}
