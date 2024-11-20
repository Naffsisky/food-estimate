<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $program_studi = [
            'Informatika',
            'Sistem Informasi',
            'Teknik Komputer',
            'Teknik Elektro',
            'Manajemen Informatika'
        ];

        for ($i = 0; $i < 50; $i++) {
            $tahun = $faker->numberBetween(19, 23);
            $kode_fakultas = str_pad($faker->numberBetween(1, 99), 2, '0', STR_PAD_LEFT);
            $kode_prodi = str_pad($faker->numberBetween(1, 999), 4, '0', STR_PAD_LEFT);
            $nomor_urut = str_pad($faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
            $npm = $tahun . $kode_fakultas . $kode_prodi . $nomor_urut;

            DB::table('mahasiswas')->insert([
                'nama' => $faker->name,
                'npm' => $npm,
                'alamat' => $faker->address,
                'program_studi' => $faker->randomElement($program_studi),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
