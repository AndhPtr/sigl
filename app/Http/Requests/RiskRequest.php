<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiskRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust authorization as necessary
    }

    public function rules()
    {
        return [
            'kategori_risiko' => 'required',
            'aset_id' => 'required',
            'risiko' => 'required',
            'penyebab' => 'required',
            'dampak' => 'required',
            'severity' => 'required|interger',
            'occurence' => 'required|interger',
            'detection' => 'required|interger',
            'rpn' => 'required|interger',
            'rpn_level' => 'required',
        ];
    }
}