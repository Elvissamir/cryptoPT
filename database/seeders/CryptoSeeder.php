<?php

namespace Database\Seeders;

use App\Models\Crypto;
use Illuminate\Database\Seeder;

class CryptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cryptosJsonData = file_get_contents("./cryptosDB.json");
        $cryptosData = json_decode($cryptosJsonData);
        
        foreach($cryptosData as $crypto) {
            Crypto::create([
                'cg_id' => $crypto->id,
            ]);
        };
    }
}
