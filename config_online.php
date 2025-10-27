<?php
// sesuaikan dengan environment Anda
return [
    'db' => [
        'host' => 'sis.istiqomahberamal.com',
        'dbname' => 'istt1637_jadwal_shalat',
        'user' => 'istt1637_sis_jadwal',
        'pass' => 'Rafauw_280212',
        'charset' => 'utf8mb4'
    ],
    // Kemenag API token (jika Anda punya, masukkan di sini)
    'kemenag' => [
        'token' => '', // <-- isi token jika ada
        // contoh endpoint base:
        'base' => 'https://bimasislam.kemenag.go.id/apiv1'
    ],
    // fallback API (public wrappers) jika tidak punya token
    'fallback_jadwal' => 'https://api.myquran.com/v1/sholat/jadwal'
];
