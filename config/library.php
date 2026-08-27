<?php

return [
    // Lama peminjaman default (hari) untuk menghitung tanggal jatuh tempo.
    'loan_days' => (int) env('LIBRARY_LOAN_DAYS', 7),

    // Denda keterlambatan per hari (Rupiah).
    'fine_per_day' => (int) env('LIBRARY_FINE_PER_DAY', 1000),
];
