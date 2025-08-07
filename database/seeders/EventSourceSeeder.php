<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_sources')->insert([
            [
                'name' => '온오프믹스 IT/기술',
                'url' => 'https://www.onoffmix.com/event/list?c=it',
                'description' => '온오프믹스에 올라오는 IT/기술 관련 행사 목록',
                'is_active' => true,
                'list_selector' => '#content > div > div.content_right > ul',
                'item_selector' => 'li',
                'title_selector' => 'h5',
                'date_selector' => 'div.date',
                'url_selector' => 'a',
                'description_selector' => 'p',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}