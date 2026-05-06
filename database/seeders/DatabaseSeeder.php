<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kriteria;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user admin
        User::create([
            'name' => 'Jetli Rikardo Manik, S.Kom',
            'email' => 'pandianganronny@gmail.com',
            'nip' => '223520053',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat user petugas
        User::create([
            'name' => 'Ronny Hartono Pandiangan, S.Kom',
            'email' => 'ronny@gmail.com',
            'nip' => '223520043',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);
        User::create([
            'name' => 'Risto Siregar, S.Kom',
            'email' => 'risto@gmail.com',
            'nip' => '223520041',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // Buat kriteria standar
        $kriteria = [
            ['kode' => 'C1', 'nama' => 'Tingkat Kerusakan', 'tipe' => 'benefit', 'bobot' => 0.30, 'satuan' => '%', 'urutan' => 1],
            ['kode' => 'C2', 'nama' => 'Volume Kendaraan', 'tipe' => 'benefit', 'bobot' => 0.25, 'satuan' => 'kendaraan/hari', 'urutan' => 2],
            ['kode' => 'C3', 'nama' => 'Panjang Jalan Rusak', 'tipe' => 'benefit', 'bobot' => 0.20, 'satuan' => 'meter', 'urutan' => 3],
            ['kode' => 'C4', 'nama' => 'Akses Penting', 'tipe' => 'benefit', 'bobot' => 0.15, 'satuan' => 'skala 1-100', 'urutan' => 4],
            ['kode' => 'C5', 'nama' => 'Biaya Perbaikan', 'tipe' => 'cost', 'bobot' => 0.10, 'satuan' => 'juta rupiah', 'urutan' => 5],
        ];

        foreach ($kriteria as $k) {
            Kriteria::create($k);
        }
    }
}