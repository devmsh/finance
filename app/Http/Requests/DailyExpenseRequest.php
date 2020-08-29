<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyExpenseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.note' => 'required',
            '*.amount' => 'required',
            '*.wallet_id' => 'required',
            '*.category_id' => 'required',
        ];
    }
}
