<?php

namespace Tests\Feature;

use App\Http\Controllers\PoaController;
use App\Models\Employee;
use App\Models\Lpj;
use App\Models\Poa;
use App\Models\Rab;
use App\Models\RabItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PoaBorrowedNameClaimTest extends TestCase
{
    use RefreshDatabase;

    public function test_borrowed_name_is_used_on_claim_and_credit_goes_to_executor()
    {
        // Employees
        $irene = Employee::create([
            'nama' => 'Irene Fordatkosu',
            'nip' => 'IRN-001',
            'tanggal_lahir' => '1990-01-01',
            'pangkat_golongan' => 'I/a',
            'jabatan' => 'Staf',
        ]);
        $cindi = Employee::create([
            'nama' => 'Cindi Claudia Latusanay, A.Md.Kes',
            'nip' => 'CND-001',
            'tanggal_lahir' => '1989-01-01',
            'pangkat_golongan' => 'III/d',
            'jabatan' => 'ASN',
        ]);
        $makda = Employee::create([
            'nama' => 'Makdalena Ilely, S.Kep., Ns',
            'nip' => 'MKD-001',
            'tanggal_lahir' => '1988-01-01',
            'pangkat_golongan' => 'III/d',
            'jabatan' => 'ASN',
        ]);

        // User Irene (non-admin)
        $user = User::factory()->create([
            'employee_id' => $irene->id,
            'role' => 'user',
        ]);

        // RAB with darat & seberang targets
        $rab = Rab::create([
            'komponen' => 'komp1',
            'rincian_menu' => 'Transport',
            'kegiatan' => 'Kunjungan Rumah Ibu Hamil Risiko Tinggi',
            'total' => 0,
        ]);
        RabItem::create([
            'rab_id' => $rab->id,
            'label' => 'Transport Darat',
            'type' => 'transport_darat',
            'factors' => [
                ['label' => 'desa', 'value' => 2],
            ],
            'unit_price' => 1,
            'subtotal' => 2,
        ]);
        RabItem::create([
            'rab_id' => $rab->id,
            'label' => 'Transport Laut',
            'type' => 'transport_laut',
            'factors' => [
                ['label' => 'desa', 'value' => 1],
            ],
            'unit_price' => 1,
            'subtotal' => 1,
        ]);

        // POA with Irene as participant
        $poa = Poa::create([
            'year' => 2025,
            'rab_id' => $rab->id,
            'kegiatan' => 'Kunjungan Rumah Ibu Hamil Risiko Tinggi',
            'planned_total' => 0,
            'created_by' => $user->id,
        ]);
        $poa->participants()->create([
            'employee_id' => $irene->id,
            'role' => 'ANGGOTA',
        ]);

        // Prepare claim request for July with borrowed map: Irene -> Makdalena
        $controller = new PoaController();
        $request = Request::create('/poa/'.$poa->id.'/claim', 'POST', [
            'month' => 7,
            'no_surat_sppt' => 'TEST-022-SPPT',
            'no_surat_sppd' => 'TEST-022-SPPD',
            'jumlah_desa_darat' => 2,
            'jumlah_desa_seberang' => 1,
            'borrowed_map' => json_encode([$irene->id => $makda->id]),
        ]);
        $request->setUserResolver(fn() => $user);

        $response = $controller->claim($request, $poa);
        $this->assertTrue(method_exists($response, 'getSession')); // redirect response

        // Fetch created LPJs
        $lpjs = Lpj::where('kegiatan', 'Kunjungan Rumah Ibu Hamil Risiko Tinggi')->get();
        $this->assertGreaterThanOrEqual(1, $lpjs->where('type', 'SPPT')->count(), 'SPPT not created');
        $this->assertGreaterThanOrEqual(1, $lpjs->where('type', 'SPPD')->count(), 'SPPD not created');

        $sppt = $lpjs->firstWhere('type', 'SPPT');
        $sppd = $lpjs->firstWhere('type', 'SPPD');
        $spptP = $sppt->participants()->first();
        $sppdP = $sppd->participants()->first();

        $this->assertNotNull($spptP, 'SPPT participant missing');
        $this->assertNotNull($sppdP, 'SPPD participant missing');

        // Document should use borrowed (Makdalena), saldo credited to executor (Irene)
        $this->assertSame($makda->id, (int) $spptP->employee_id, 'SPPT document name not borrowed (Makdalena)');
        $this->assertSame($irene->id, (int) $spptP->credited_employee_id, 'SPPT credit not to executor (Irene)');
        $this->assertSame($makda->id, (int) $sppdP->employee_id, 'SPPD document name not borrowed (Makdalena)');
        $this->assertSame($irene->id, (int) $sppdP->credited_employee_id, 'SPPD credit not to executor (Irene)');
    }
}

