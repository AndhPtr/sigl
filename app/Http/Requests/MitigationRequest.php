<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MitigationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'aset_kritis' => 'required',
            'risiko' => 'required',
            'rpn' => 'required',
            'rpn_level' => 'required',
            'tindakan_mitigasi' => 'required',
        ];
    }
}
