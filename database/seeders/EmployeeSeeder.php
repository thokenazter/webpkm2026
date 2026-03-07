<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'nama' => 'Thobias Edwin Dasmaselah, S.KM',
                'nip' => 'NIP3K. 19950612 202421 1 005',
                'tanggal_lahir' => '1995-06-12',
                'pangkat_golongan' => 'IX',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Cindi Claudia Latusanay, A.Md.Kes',
                'nip' => 'NIP3K. 19970113 202421 2 012',
                'tanggal_lahir' => '1997-01-13',
                'pangkat_golongan' => 'VIII',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Kardioka Silaban, A.Md.Keb',
                'nip' => 'NIP. 199302222019032018',
                'tanggal_lahir' => '1993-02-22',
                'pangkat_golongan' => 'II/d',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Jacob Galandjindjinay, S.Kep., Ns',
                'nip' => 'NIP3K. 199412242024211005',
                'tanggal_lahir' => '1994-12-24',
                'pangkat_golongan' => 'X',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Gillian Tery A.Md. Gz',
                'nip' => 'NIP3K. 19970724202312002',
                'tanggal_lahir' => '1997-09-24',
                'pangkat_golongan' => 'VIII',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Istika Sari Barend, A.Md.Keb',
                'nip' => 'NIP3K. 198904072023212004',
                'tanggal_lahir' => '1989-04-07',
                'pangkat_golongan' => 'VIII',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Nunuk Puspa Ningrum, A.Md.AK',
                'nip' => 'NIP. 199508132022022001',
                'tanggal_lahir' => '1995-08-13',
                'pangkat_golongan' => 'II/c',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Jolanda Boger, A.Md.Kep',
                'nip' => 'NIP. 197605162008042001',
                'tanggal_lahir' => '1976-05-16',
                'pangkat_golongan' => 'III/c',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Makdalena Ilely, S.Kep., Ns',
                'nip' => 'NIP. 198304052006042022',
                'tanggal_lahir' => '1983-04-05',
                'pangkat_golongan' => 'III/d',
                'jabatan' => 'Kepala Puskesmas',
            ],
            [
                'nama' => 'Rahima, A.Md.Keb',
                'nip' => 'NIP3K. 199302032023212007',
                'tanggal_lahir' => '1993-02-03',
                'pangkat_golongan' => 'VIII',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Since Korisen, A.Md.Kep',
                'nip' => 'NIP. 199001062011012006',
                'tanggal_lahir' => '1990-01-06',
                'pangkat_golongan' => 'II/c',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Hetereda Ketno, S.Kep .Ns',
                'nip' => 'Tenaga Sukarela',
                'tanggal_lahir' => '2025-01-01',
                'pangkat_golongan' => null,
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Irene Fordatkosu, S.KM',
                'nip' => 'Tenaga Sukarela',
                'tanggal_lahir' => '2025-01-01',
                'pangkat_golongan' => null,
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Regina Madidi, A.Md.Keb',
                'nip' => 'Tenaga Sukarela',
                'tanggal_lahir' => '2025-01-01',
                'pangkat_golongan' => null,
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Amos Naheson Djabutafuan, S.Kep., Ners',
                'nip' => 'NIP. 199911302025061006',
                'tanggal_lahir' => '1999-11-30',
                'pangkat_golongan' => 'III/b',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Onalin Ester E. Habibuw, S.Kep., Ners',
                'nip' => 'NIP. 199811192025062006',
                'tanggal_lahir' => '1998-11-19',
                'pangkat_golongan' => 'III/b',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Wa Ode Kurniati Jan Jan, A.Md.Keb',
                'nip' => 'NIP. 199908102025062005',
                'tanggal_lahir' => '1999-10-08',
                'pangkat_golongan' => 'II/c',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Irene Selespina Ngarbingan, A.Md.Kep',
                'nip' => 'NIP. 199896102025062007',
                'tanggal_lahir' => '1998-09-10',
                'pangkat_golongan' => 'II/c',
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'Margaretha Mangar',
                'nip' => 'Tenaga Sukarela',
                'tanggal_lahir' => '2025-01-01',
                'pangkat_golongan' => null,
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'apt. Ardiansa, S.Farm',
                'nip' => 'Penugasan Khusus',
                'tanggal_lahir' => '2000-02-21',
                'pangkat_golongan' => null,
                'jabatan' => 'Staf Puskesmas',
            ],
            [
                'nama' => 'dr. Rahmatan, S.Ked',
                'nip' => 'Penugasan Khusus',
                'tanggal_lahir' => '1997-10-10',
                'pangkat_golongan' => null,
                'jabatan' => 'Dokter',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
