<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::insert('INSERT INTO password (pass, game_id) VALUES 
        (8, 1), (3, 1), (9, 1), (2, 1), 
        (1, 2), (7, 2), (4, 2), (9, 2),
        (5, 3), (2, 3), (1, 3), (7, 3),
        (6, 4), (9, 4), (4, 4), (8, 4),
        (8, 5), (3, 5), (0, 5), (6, 5), 
        (1, 6), (9, 6), (5, 6), (4, 6),
        (3, 7), (8, 7), (6, 7), (1, 7),
        (4, 8), (7, 8), (2, 8), (9, 8),
        (9, 9), (1, 9), (8, 9), (5, 9),
        (3, 10), (0, 10), (6, 10), (7, 10)');

    }
}
