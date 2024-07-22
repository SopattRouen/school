<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('user_type')->insert(
            [
                ['name' => 'Admin'],
                ['name' => 'Staff'],
                ['name' => 'Student'],
            ]
        );

        $users=[
            [
                'type_id' => 1,
                'name' => 'Sopatt Kh',
                'email' => 'sopatt123@gmail.com',
                'phone' => '060486849',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 2,
                'name' => 'Dara Koko',
                'email' => 'dara123@gmail.com',
                'phone' => '090486842',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 3,
                'name' => 'Van Kh',
                'email' => 'Van123@gmail.com',
                'phone' => '088406849',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 3,
                'name' => 'Nora Voern',
                'email' => 'nora123@gmail.com',
                'phone' => '077756849',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 3,
                'name' => 'Koko Na',
                'email' => 'koko123@gmail.com',
                'phone' => '090678842',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 3,
                'name' => 'Vanna Rosa',
                'email' => 'rosa123@gmail.com',
                'phone' => '089429849',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 3,
                'name' => 'Voern Rani',
                'email' => 'rani123@gmail.com',
                'phone' => '077753840',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 3,
                'name' => 'Na Bora',
                'email' => 'oren123@gmail.com',
                'phone' => '012672842',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ],
            [
                'type_id' => 3,
                'name' => 'Kouern Rosa',
                'email' => 'rrasa123@gmail.com',
                'phone' => '089028849',
                'password' => bcrypt('123456'),
                'avatar' => '',
            ]

        ];
        DB::table('user')->insert($users);
    }
}
