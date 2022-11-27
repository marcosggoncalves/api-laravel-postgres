<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Gerentes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gerentes')->insert([
            [
                'nome' => 'Marcos',
                'password' => Hash::make('1234'),
                'email' => 'marcoslopesg7@gmail.com',
                'nivel' => 1,
                'created_at' => 'now()',
                'updated_at' => 'now()'
            ],
            [
                'nome' => 'Marcos',
                'password' => Hash::make('1234'),
                'email' => 'marcoslopes5687@gmail.com',
                'nivel' => 2,
                'created_at' => 'now()',
                'updated_at' => 'now()'
            ]
        ]);
    }
}
