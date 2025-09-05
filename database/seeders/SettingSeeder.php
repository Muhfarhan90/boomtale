<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['setting_key' => 'site_name', 'setting_value' => 'Boomtale'],
            ['setting_key' => 'site_email', 'setting_value' => 'admin@boomtale.com'],
            ['setting_key' => 'site_phone', 'setting_value' => '+62 812-3456-7890'],
            ['setting_key' => 'site_description', 'setting_value' => 'Platform digital marketplace terpercaya'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                ['setting_value' => $setting['setting_value']]
            );
        }
    }
}
