<?php

return [
    'ledger' => [
        // Default akun untuk posting LPJ ke BKU jika mapping khusus tidak tersedia
        'lpj_default_account' => env('BOK_LPJ_DEFAULT_ACCOUNT', 'BANK'), // BANK|CASH

        // Mapping akun berdasarkan transport_mode LPJ (opsional)
        'lpj_account_by_transport' => [
            'DARAT' => env('BOK_LPJ_ACCOUNT_DARAT', null),
            'LAUT' => env('BOK_LPJ_ACCOUNT_LAUT', null),
        ],

        // Posting untuk biaya non-peserta (snack/konsumsi/penggandaan dll.)
        'non_participant_account' => env('BOK_NON_PARTICIPANT_ACCOUNT', 'CASH'), // BANK|CASH
        'exclude_types' => [
            // Hindari double count karena sudah dijurnal dari peserta LPJ
            'transport_darat', 'transport_laut', 'uang_harian',
        ],
        // Jika type kosong pada RabItem, gunakan heuristik label
        'include_label_keywords' => [
            'snack', 'konsumsi', 'penggandaan', 'bahan', 'atk', 'makanan', 'minum', 'kue'
        ],

        // Control auto-posting of non-participant costs at LPJ creation
        'auto_post_non_participant' => env('BOK_LEDGER_AUTO_POST_NON_PARTICIPANT', false),
    ],

    // Default desa yang diprioritaskan saat auto-isi POA (dapat diedit di UI)
    'villages' => [
        'default_names' => [
            // Urutan menentukan prioritas pengisian otomatis
            'DARAT' => [
                'Kabalsiang',
                'Benjuring',
            ],
            'SEBERANG' => [
                'Kumul',
                'Batuley',
                'Kompane',
            ],
        ],
    ],
];
