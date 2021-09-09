<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Crypto;
use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'DemoUser@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Portfolio::create(['user_id' => $user->id]);

        $cryptoA = Crypto::where('cg_id', 'bitcoin')->get()->toArray();
        $cryptoB = Crypto::where('cg_id', 'theta-token')->get()->toArray();
        $cryptoC = Crypto::where('cg_id', 'theta-fuel')->get()->toArray();

        $user->portfolio->addCrypto($cryptoA[0]['id'], 1);
        $user->portfolio->addCrypto($cryptoB[0]['id'], 100);
        $user->portfolio->addCrypto($cryptoC[0]['id'], 100);

    }
}
