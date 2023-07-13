<?php

namespace Database\Seeders;

use App\Models\PaymentGatewaySetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EntitymstSeeder::class,
            RoleSeeder::class,
            CampaignCategorySeeder::class,
            PaymentGatewaySettingSeeder::class,
            SocialPlatformSettingSeeder::class,
            UploadTypesSeeder::class,
    	]);
    }
}
