<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'total_income' => 'required|numeric',
            'must_have' => 'required|numeric',
            'min_saving' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
