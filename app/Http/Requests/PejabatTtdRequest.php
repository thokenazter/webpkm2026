<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PejabatTtdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama pejabat harus diisi.',
            'nama.max' => 'Nama pejabat maksimal 255 karakter.',
            'desa.required' => 'Nama desa harus diisi.',
            'desa.max' => 'Nama desa maksimal 255 karakter.',
            'jabatan.required' => 'Jabatan harus diisi.',
            'jabatan.max' => 'Jabatan maksimal 255 karakter.',
        ];
    }
}