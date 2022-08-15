<?php

use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('achievements')->truncate();
        DB::table('achievement_details')->truncate();
        DB::table('achievement_detail_company')->truncate();
        DB::table('company_achievement')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $achievement = \App\Models\Achievement::create(
            [
                'achievement_slug' => 'business-profile',
                'title_en' => 'Business Profile',
                'title_id' => 'Profil Usaha',
                'reward_point' => 100,
                'icon' => 'img/048-telephone-1.png'
            ]
        );
        $details = [
            [
                'slug' => 'business_type',
                'title_en' => 'Business Type',
                'title_id' => 'Tipe Usaha'
            ],
//            [
//                'slug' => 'account_security',
//                'title_en' => 'Account and Security',
//                'title_id' => 'Informasi Akun dan Keamanan'
//            ],
            [
                'slug' => 'about_company',
                'title_en' => 'About My Company',
                'title_id' => 'Tentang Usaha Saya'
            ],
            [
                'slug' => 'address_company',
                'title_en' => 'Company Address',
                'title_id' => 'Alamat Usaha Saya'
            ],
            [
                'slug' => 'contact_us',
                'title_en' => 'Company Contact information',
                'title_id' => 'Nomor Kontak dan Email Usaha Saya'
            ],
            [
                'slug' => 'company_logo',
                'title_en' => 'Company Logo',
                'title_id' => 'Logo Bisnis'
            ],
            [
                'slug' => 'company_banner',
                'title_en' => 'Company Banner',
                'title_id' => 'Gambar Banner'
            ],
            [
                'slug' => 'seo',
                'title_en' => 'SEO',
                'title_id' => 'SEO'
            ],
            [
                'slug' => 'bank_account',
                'title_en' => 'Bank Account',
                'title_id' => 'Akun Rekening Bank'
            ],
//            [
//                'slug' => 'kyc',
//                'title_en' => 'My Business Legality',
//                'title_id' => 'Legalitas Usaha Saya'
//            ],
        ];
        foreach ($details as $detail) {
            $achievement->details()->create($detail);
        }
        $companies = \App\Models\Company::all();
        foreach ($companies as $company) {

            if ($company->categories->count() > 0) {
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('business_type')->first(),
                        ['achievement_status' => 1]);
            } else {
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('business_type')->first(),
                        ['achievement_status' => 0]);
            }
            if ($company->agent && $company->agent->first_name !== null):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('account_security')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('account_security')->first(),
                        ['achievement_status' => 0]);
            endif;

            if ($company->short_description !== null):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('about_company')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('about_company')->first(),
                        ['achievement_status' => 0]);
            endif;

            if ($company->address_company !== null && $company->id_city !== null && $company->google_place_id!==null):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('address_company')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('address_company')->first(),
                        ['achievement_status' => 0]);
            endif;


            if ($company->phone_company !== null && $company->email_company !== null && $company->twitter_company && $company->facebook_company !== null && $company->agent->email!=null):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('contact_us')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('contact_us')->first(),
                        ['achievement_status' => 0]);
            endif;

            if ($company->logo !== null):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('company_logo')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('company_logo')->first(),
                        ['achievement_status' => 0]);
            endif;

            if ($company->banner !== null):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('company_banner')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('company_banner')->first(),
                        ['achievement_status' => 0]);
            endif;

            if ($company->title !== null && $company->description !== null && $company->keywords !== null):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('seo')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('seo')->first(),
                        ['achievement_status' => 0]);
            endif;

            if ($company->bank):
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('bank_account')->first(),
                        ['achievement_status' => 1]);
            else:
                $company->achievement_details()
                    ->attach(\App\Models\AchievementDetail::whereSlug('bank_account')->first(),
                        ['achievement_status' => 0]);
            endif;
//            if ($company->kyc && $company->kyc->status == 'approved'):
//                $company->achievement_details()
//                    ->attach(\App\Models\AchievementDetail::whereSlug('kyc')->first(),
//                        ['achievement_status' => 1]);
//            else:
//                $company->achievement_details()
//                    ->attach(\App\Models\AchievementDetail::whereSlug('kyc')->first(),
//                        ['achievement_status' => 0]);
//            endif;
        }

        foreach (\App\Models\Achievement::all() as $item) {
            $number = 1;
            foreach ($item->details()->orderBy('id', 'asc')->get() as $detail) {
                $detail->update(['order_number' => $number++]);
            }

            foreach (\App\Models\Company::all() as $company) {
                $check = $company->achievement_details()->whereHas('achievement', function ($a) use ($item){
                    $a->where('achievement_slug', $item->achievement_slug);
                })->where('achievement_status',0)->first();
                if ($check){
                    $company->achievements()
                        ->attach($item,
                            ['company_achievement_status' => 0]);
                }else{
                    $company->achievements()
                        ->attach($item,
                            ['company_achievement_status' => 1]);
                }
            }
        }
//        DB::table('achievement_detail_company')->update(['achievement_status'=>0]);
//        DB::table('company_achievement')->update(['company_achievement_status'=>0]);


    }
}
