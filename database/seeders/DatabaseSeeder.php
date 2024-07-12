<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // // \App\Models\User::factory()->create([
        // //     'name' => 'Test User',
        // //     'email' => 'test@example.com',
        // // ]);

        // destination
        // $data = [
        //     'Ambon',
        //     'Balikpapan',
        //     'Bandung',
        //     'Banjarmasin',
        //     'Batam',
        //     'Bekasi',
        //     'Bengkulu',
        //     'Bogor',
        //     'Cirebon',
        //     'Denpasar',
        //     'Depok',
        //     'Jakarta',
        //     'Jambi',
        //     'Jayapura',
        //     'Kendari',
        //     'Kupang',
        //     'Makassar',
        //     'Malang',
        //     'Manado',
        //     'Mataram',
        //     'Medan',
        //     'Padang',
        //     'Palembang',
        //     'Palu',
        //     'Pekanbaru',
        //     'Pontianak',
        //     'Samarinda',
        //     'Semarang',
        //     'Serang',
        //     'Sorong',
        //     'Surabaya',
        //     'Tangerang',
        //     'Tanjungpinang',
        //     'Yogyakarta',
        // ];

        // foreach ($data as $key => $value) {
        //     DB::table('destinations')->insert([
        //         'name' => $value,
        //     ]);
        // }

        // // customer
        // $data = [
        //     [
        //         'code'  => 'C-001',
        //         'name' => 'PT. ABC',
        //         'email' => 'admin@abc.com',
        //         'phone' => '08123456789',
        //         'address' => 'Jl. ABC No. 123',
        //     ],
        //     [
        //         'code'  => 'C-002',
        //         'name' => 'PT. Alizwell',
        //         'email' => 'admin@alizwell.id',
        //         'phone' => '08123456789',
        //         'address' => 'Jl. Alizwell No. 123',
        //     ],
        // ];
        // foreach ($data as $key => $value) {
        //     DB::table('customers')->insert($value);
        // }


        // customer_prices
        $outlet_id = [1, 2, 3];
        $destination_id = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34];
        $armada = [1, 2, 3];
        $estimation = [1, 2, 3, 4, 5, 6, 7];
        $price = [100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000];
        $data = [];
        foreach ($outlet_id as $outlet) {
        foreach ($destination_id as $destination) {
        foreach ($armada as $arm) {
        $data[] = [
        'customer_id' => 1,
        'outlet_id' => $outlet,
        'destination_id' => $destination,
        'armada' => $arm,
        'price' => $price[array_rand($price)],
        'estimation' => $estimation[array_rand($estimation)],
        ];
                }
            }
        }
        DB::table('customer_prices')->insert($data);
    }
}
