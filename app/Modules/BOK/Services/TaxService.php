<?php

namespace App\Modules\BOK\Services;

use App\Modules\BOK\Models\TaxEntry;

class TaxService
{
    /** Calculate tax obligation for a subject (LPJ/SPJ). */
    public function calculate(object $subject, array $data): TaxEntry
    {
        $entry = new TaxEntry();
        $entry->tax_type = $data['tax_type'] ?? 'PPN';
        $entry->base_amount = (float) ($data['base_amount'] ?? 0);
        $entry->tax_amount = (float) ($data['tax_amount'] ?? 0);
        $entry->status = $data['status'] ?? 'Pending';
        $entry->due_date = $data['due_date'] ?? null;
        $entry->source()->associate($subject);
        $entry->save();
        return $entry;
    }

    /** Mark a tax entry as paid and optionally verified later. */
    public function markPaid(TaxEntry $entry, ?string $evidencePath = null): TaxEntry
    {
        $entry->status = 'Paid';
        $entry->paid_at = now();
        if ($evidencePath) $entry->evidence_path = $evidencePath;
        $entry->save();
        return $entry;
    }
}

