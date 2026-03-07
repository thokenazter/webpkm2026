<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TibaBerangkatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tibaBerangkatId = $this->route('tiba_berangkat') ? $this->route('tiba_berangkat')->id : null;

        return [
            'no_surat' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tiba_berangkat')->ignore($tibaBerangkatId)
            ],
            'desa' => 'required|array|min:1',
            'desa.*.pejabat_ttd_id' => 'required|exists:pejabat_ttd,id',
            'desa.*.tanggal_kunjungan' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'no_surat.required' => 'Nomor surat harus diisi.',
            'no_surat.unique' => 'Nomor surat sudah digunakan.',
            'no_surat.max' => 'Nomor surat maksimal 255 karakter.',
            'desa.required' => 'Minimal satu desa harus dipilih.',
            'desa.min' => 'Minimal satu desa harus dipilih.',
            'desa.*.pejabat_ttd_id.required' => 'Pejabat TTD harus dipilih.',
            'desa.*.pejabat_ttd_id.exists' => 'Pejabat TTD tidak valid.',
            'desa.*.tanggal_kunjungan.required' => 'Tanggal kunjungan harus diisi.',
            'desa.*.tanggal_kunjungan.date' => 'Format tanggal kunjungan tidak valid.',
        ];
    }

    protected function prepareForValidation()
    {
        // Remove empty desa entries
        if ($this->has('desa')) {
            $desa = array_filter($this->desa, function($item) {
                return !empty($item['pejabat_ttd_id']) || !empty($item['tanggal_kunjungan']);
            });
            
            $this->merge([
                'desa' => array_values($desa)
            ]);
        }
    }
}