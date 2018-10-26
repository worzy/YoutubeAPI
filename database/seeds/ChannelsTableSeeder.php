<?php

use Illuminate\Database\Seeder;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channels')->insert([
            ['channel_name' => 'GlobalCyclingNetwork'],
            ['channel_name' => 'globalmtb'],
        ]);
    }
}
