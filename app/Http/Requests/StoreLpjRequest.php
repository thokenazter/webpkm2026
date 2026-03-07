<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLpjRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['SPPT', 'SPPD'])],
            'kegiatan' => ['required', 'string', 'max:255'],
            'no_surat' => ['required', 'string', Rule::unique('lpjs')->ignore($this->route('lpj'))],
            'tanggal_surat' => ['required', 'string', 'max:255'],
            'tanggal_kegiatan' => ['required', 'string', 'max:255'],
            'transport_mode' => ['nullable', Rule::in(['Perahu', 'Speedboat', 'Kapal Motor', 'Pompong', 'Lainnya', 'DARAT', 'LAUT'])],
            'jumlah_desa_darat' => ['required', 'integer', 'min:0'],
            'desa_tujuan_darat' => ['nullable', 'string'],
            'jumlah_desa_seberang' => ['required', 'integer', 'min:0'],
            'desa_tujuan_seberang' => ['nullable', 'string'],
            'participants' => ['required', 'array', 'min:1'],
            'participants.*.employee_id' => ['required', 'exists:employees,id'],
            'participants.*.role' => ['nullable', Rule::in(['KETUA', 'ANGGOTA', 'PENDAMPING', 'LAINNYA'])],
            'participants.*.credited_employee_id' => ['nullable', 'exists:employees,id'],
            'participants.*.lama_tugas_hari' => ['required', 'integer', 'min:1'],
            'participants.*.transport_amount' => ['required', 'numeric', 'min:0'],
            'participants.*.per_diem_rate' => ['required', 'numeric', 'min:0'],
            'participants.*.per_diem_days' => ['required', 'integer', 'min:0'],
            'participants.*.per_diem_amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateBusinessRules($validator);
        });
    }

    /**
     * Validate business rules for SPPT/SPPD
     */
    protected function validateBusinessRules($validator)
    {
        $type = $this->type;
        $jumlahDesaDarat = (int) ($this->jumlah_desa_darat ?? 0);
        $jumlahDesaSeberang = (int) ($this->jumlah_desa_seberang ?? 0);

        // SPPT hanya untuk desa DARAT
        if ($type === 'SPPT') {
            if ($jumlahDesaDarat <= 0) {
                $validator->errors()->add('jumlah_desa_darat', 'SPPT harus memiliki minimal 1 desa darat.');
            }
            if ($jumlahDesaSeberang > 0) {
                $validator->errors()->add('jumlah_desa_seberang', 'SPPT tidak boleh memiliki desa seberang.');
            }

            // Validasi transport amount = Rp 70.000 x jumlah desa darat per peserta
            $expectedTransport = 70000 * $jumlahDesaDarat;
            if ($this->participants) {
                foreach ($this->participants as $index => $participant) {
                    if (($participant['transport_amount'] ?? 0) != $expectedTransport) {
                        $validator->errors()->add(
                            "participants.{$index}.transport_amount",
                            "Transport amount harus Rp " . number_format($expectedTransport, 0, ',', '.') . " (Rp 70.000 x {$jumlahDesaDarat} desa) untuk SPPT."
                        );
                    }
                    if (($participant['per_diem_amount'] ?? 0) > 0) {
                        $validator->errors()->add(
                            "participants.{$index}.per_diem_amount",
                            'Uang harian harus 0 untuk SPPT.'
                        );
                    }
                }
            }
        }

        // SPPD hanya untuk desa SEBERANG
        if ($type === 'SPPD') {
            if ($jumlahDesaSeberang <= 0) {
                $validator->errors()->add('jumlah_desa_seberang', 'SPPD harus memiliki minimal 1 desa seberang.');
            }
            if ($jumlahDesaDarat > 0) {
                $validator->errors()->add('jumlah_desa_darat', 'SPPD tidak boleh memiliki desa darat.');
            }

            // Validasi transport amount = Rp 70.000 x jumlah desa seberang per peserta
            $expectedTransport = 70000 * $jumlahDesaSeberang;
            $expectedPerDiemPerPeserta = 150000 * $jumlahDesaSeberang; // Per pegawai dikali jumlah desa
            
            if ($this->participants) {
                foreach ($this->participants as $index => $participant) {
                    if (($participant['transport_amount'] ?? 0) != $expectedTransport) {
                        $validator->errors()->add(
                            "participants.{$index}.transport_amount",
                            "Transport amount harus Rp " . number_format($expectedTransport, 0, ',', '.') . " (Rp 70.000 x {$jumlahDesaSeberang} desa) untuk SPPD."
                        );
                    }
                    
                    // Validasi uang harian per peserta = Rp 150.000 x jumlah desa
                    if (($participant['per_diem_amount'] ?? 0) != $expectedPerDiemPerPeserta) {
                        $validator->errors()->add(
                            "participants.{$index}.per_diem_amount",
                            "Uang harian harus Rp " . number_format($expectedPerDiemPerPeserta, 0, ',', '.') . " (Rp 150.000 x {$jumlahDesaSeberang} desa) per peserta untuk SPPD."
                        );
                    }
                }
            }
        }
    }
}
