<?php

use App\Timezone;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = time();
        foreach (timezone_identifiers_list() as $zone) {
            date_default_timezone_set($zone);
            $zones['offset'] = date('P', $timestamp);
            $zones['diff_from_gtm'] = 'UTC/GMT '.date('P', $timestamp);
            $country = Timezone::getLocation($zone);
            $zones['country_code'] = $country['country_code'];
            $zones['country_name'] = $country['country_name'];
            Timezone::updateOrCreate(['name' => $zone], $zones);
        }
    }
}
