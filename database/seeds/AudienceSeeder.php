<?php

use Illuminate\Database\Seeder;

class AudienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\BlogPost::query()->update([
            'audience_id' => null
        ]);
        \App\Models\TagBlog::query()->update([
            'audience_id' => null
        ]);
        \App\Models\CategoryBlog::query()->update([
            'audience_id' => null
        ]);
        $audiences = [
            [
                'audience_name' => 'all'
            ],
            [
                'audience_name' => 'customer'
            ],
            [
                'audience_name' => 'provider'
            ],
            [
                'audience_name' => 'supplier'
            ],
            [
                'audience_name' => 'customer_klhk'
            ],
            [
                'audience_name' => 'provider_klhk'
            ],
            [
                'audience_name' => 'supplier_klhk'
            ],
            [
                'audience_name' => 'internal'
            ],
        ];
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('audiences')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        array_map(function ($audience) {
            \App\Models\Audience::create($audience);
        }, $audiences);
        $id = \App\Models\Audience::where('audience_name', 'customer')->first()->id;
        \App\Models\BlogPost::query()->update([
            'audience_id' => $id
        ]);
        \App\Models\TagBlog::query()->update([
            'audience_id' => $id
        ]);
        \App\Models\CategoryBlog::query()->update([
            'audience_id' => $id
        ]);


    }
}
