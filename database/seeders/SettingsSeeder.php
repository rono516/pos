<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate([
            'app_name' => "Pharmacy POS",
            'currency_symbol' => "KES",
            'warning_quantity' => 40
        ]);
    }
}
