<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['Siti Aminah', 'siti@example.com', '081234567801', 'Jl. Melati No. 1, Jakarta'],
            ['Budi Santoso', 'budi@example.com', '081234567802', 'Jl. Mawar No. 2, Bandung'],
            ['Dewi Lestari', 'dewi@example.com', '081234567803', 'Jl. Anggrek No. 3, Surabaya'],
            ['Andi Wijaya', 'andi@example.com', '081234567804', 'Jl. Kenanga No. 4, Semarang'],
            ['Rina Marlina', 'rina@example.com', '081234567805', 'Jl. Dahlia No. 5, Yogyakarta'],
        ];

        foreach ($members as $i => [$name, $email, $phone, $address]) {
            Member::firstOrCreate(
                ['code' => 'M'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT)],
                ['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address],
            );
        }
    }
}
