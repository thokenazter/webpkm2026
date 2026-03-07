<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunPegawaiSeeder extends Seeder
{
    /**
     * Seed employee accounts from pegawai.md (tab-separated: nama\temail).
     * - Uses given emails when present
     * - For blank emails, auto-generates sequential user{n}@gmail.com continuing from the last used number
     * - Creates/updates Employee and links User.employee_id
     */
    public function run(): void
    {
        $path = base_path('pegawai.md');
        if (!file_exists($path)) {
            $this->command?->warn('pegawai.md not found; skipping AkunPegawaiSeeder.');
            return;
        }

        $rows = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        if (empty($rows)) {
            $this->command?->warn('pegawai.md is empty; skipping.');
            return;
        }

        // Parse header
        $header = trim((string) $rows[0]);
        $startIdx = 0;
        if (stripos($header, 'nama') !== false && stripos($header, 'email') !== false) {
            $startIdx = 1; // skip header
        }

        // Determine next user index based on existing emails & provided numbers
        $maxIdx = 0;
        // 1) scan file-provided emails like user{n}@gmail.com
        // Helper to generate unique NIP-like code
        $genNip = function () {
            $prefix = 'AUTO-EMP-';
            $n = (int) (\App\Models\Employee::max('id') ?? 0) + 1;
            $try = 0;
            do {
                $nip = $prefix . str_pad((string) ($n + $try), 6, '0', STR_PAD_LEFT);
                $try++;
            } while (\App\Models\Employee::where('nip', $nip)->exists() && $try < 100000);
            return $nip;
        };

        for ($i = $startIdx; $i < count($rows); $i++) {
            $parts = preg_split("/\t+/u", (string) $rows[$i]);
            if (!$parts || count($parts) < 1) continue;
            $email = trim((string) ($parts[1] ?? ''));
            if ($email !== '' && preg_match('/^user(\d+)@gmail\.com$/i', $email, $m)) {
                $n = (int) $m[1];
                if ($n > $maxIdx) $maxIdx = $n;
            }
        }
        // 2) scan DB existing pattern user{n}@gmail.com
        $existing = User::where('email', 'like', 'user%@gmail.com')->pluck('email');
        foreach ($existing as $em) {
            if (preg_match('/^user(\d+)@gmail\.com$/i', (string) $em, $m)) {
                $n = (int) $m[1];
                if ($n > $maxIdx) $maxIdx = $n;
            }
        }

        $password = Hash::make('12121212');
        for ($i = $startIdx; $i < count($rows); $i++) {
            $line = (string) $rows[$i];
            $parts = preg_split("/\t+/u", $line);
            if (!$parts || count($parts) < 1) continue;
            $nama = trim((string) ($parts[0] ?? ''));
            $email = trim((string) ($parts[1] ?? ''));
            if ($nama === '') continue;

            if ($email === '') {
                // auto-generate sequential user{n}@gmail.com
                $n = $maxIdx + 1;
                // ensure unique
                while (User::where('email', 'user'.$n.'@gmail.com')->exists()) {
                    $n++;
                }
                $email = 'user'.$n.'@gmail.com';
                $maxIdx = $n;
            }

            // Upsert Employee by name with required defaults
            $employee = Employee::where('nama', $nama)->first();
            if (!$employee) {
                $employee = new Employee();
                $employee->nama = $nama;
                $employee->nip = $genNip();
                $employee->tanggal_lahir = '1990-01-01';
                $employee->pangkat_golongan = '-';
                $employee->jabatan = 'ASN';
                $employee->save();
            }

            // Upsert User by email
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = new User();
                $user->email = $email;
                $user->password = $password;
                $user->role = 'user';
                $user->approved_at = now();
            }
            // Always sync name + employee link
            $user->name = $nama;
            $user->employee_id = $employee->id;
            if (!$user->password) {
                $user->password = $password;
            }
            if (empty($user->role)) $user->role = 'user';
            if (empty($user->approved_at)) $user->approved_at = now();
            $user->save();
        }

        $this->command?->info('AkunPegawaiSeeder: selesai membuat/memperbarui akun pegawai dari pegawai.md');
    }
}
