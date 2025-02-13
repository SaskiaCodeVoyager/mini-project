<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Divisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Divisi::create([
            'nama' => 'Admin',
        ]);

        User::create([
            'username' => 'Admin',
            'email' => 'rangga@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'member', 
            'asal_sekolah' => 'SMA Negeri 1 Jakarta',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'alamat' => 'Jl. Kebon Jer',
            'no_hp' => '081234567890',
            'alamat_sekolah' => 'Jl. Kebon Jeruk',
            'no_hp_sekolah' => '081234567890',
            'divisi_id' => 1,
                'foto_pribadi' => 'admin.jpg',
        ]);
    }
}
