<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keyword;
class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keywords = [
            ['name' => 'Software companies in usa'],
            ['name' => 'Software companies in Arizona'],
            ['name' => 'Dog care app Canada'],
            ['name' => 'Tech companies'],
            ['name' => 'Marketing agency California'],
            ['name' => 'Marketing Agency Calgary'],
            ['name' => 'Software companies UK'],
            ['name' => 'Software companies Canada'],
            ['name' => 'Software companies Germany'],
            ['name' => 'Software companies Italy'],
            ['name' => 'Software companies France'],
            ['name' => 'Software companies Poland'],
            ['name' => 'Software companies Switzerland'],
            ['name' => 'Software companies Ireland'],
            ['name' => 'Software companies Netherlands'],
            ['name' => 'Software companies Sweden'],
            ['name' => 'Software companies Austria'],
        ];

        Keyword::insert($keyword);

        echo "done";
    }
}
