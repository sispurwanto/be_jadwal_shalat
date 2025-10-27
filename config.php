<?php
// sesuaikan dengan environment Anda
return [
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'jadwal_shalat',
        'user' => 'root',
        'pass' => '',
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
