<?php

namespace App\Models;

use App\Scopes\ActiveProviderScope;
use Carbon\Carbon;
use Gomodo\Discord\Notify;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Scopes\IsKlhkScope;

class Company extends Model
{
    use Notifiable;
    protected $table = 'tbl_company';
    public $primaryKey = 'id_company';
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_klhk' => 'boolean',
    ];

//    protected $fillable = ['company_name','domain','domain_memoria','email_company','phone_company','city_company','address_company','logo','status','banner','quote','short_description','twitter_company','facebook_company','color_company', 'font_color_company'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActiveProviderScope);

        $klhk_backoffice = env('KLHK_BACKOFFICE_URL', 'bupsha.' . env('APP_URL'));
        if (request()->getHttpHost() == $klhk_backoffice) {
            static::addGlobalScope(new IsKlhkScope(true));
        }
        static::created(function ($company){
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

            foreach (\App\Models\Achievement::all() as $item) {
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

            $company->payments()->sync(ListPayment::all());
//            $cc = ListPayment::where('code_payment','credit_card')->first();
//            $company->payments()->updateExistingPivot($cc->id,['charge_to' => 1]);

            $list = ListPayment::whereIn('code_payment', ['credit_card','dana','linkaja','indomaret','gopay','bca_va','akulaku','ovo_live'])->get();
            $list->each(function ($item, $key) use ($company) {
                $company->payments()->updateExistingPivot($item->id, ['charge_to' => 1]);
            });
//
//            foreach (ListPayment::all() as $item) {
//                $item->companies()->sync(Company::all());
//                foreach ($item->companies as $data) {
//                    if (in_array($item->code_payment, ['credit_card'])) {
//                        $item->companies()->updateExistingPivot($data->id_company, ['charge_to' => 1]);
//                    }
//                }
//            }
        });

        static::updated(function ($model){
            $model->fresh();
            if ($model->isDirty('google_place_id')){
                $content = '**Change Business Location ' . Carbon::now()->format('d M Y H:i:s') . '**';
                $content .= '```';
                $content .= "Company Name    : " . $model->company_name . "\n";
                $content .= "Domain Gomodo   : " . $model->domain_memoria . "\n";
                $content .= "Place ID        : " . $model->google_place_id . "\n";
                $content .= '```';
                Notify::location()->setContent(sprintf('%s',$content))->send();
            }
        });
    }

    public function associations()
    {
        return $this->belongsToMany(Association::class, 'tbl_company_association', 'id_company',
                'id_association')->withPivot('membership_id');
    }

    public function id_user_agen()
    {
        return $this->hasMany('App\Models\UserAgent', 'id_company', 'id_company');
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email_company;
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\UserAgent', 'id_company', 'id_company');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_company');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function city()
    {
        return $this->hasOne('\App\Models\City', 'id_city', 'id_city');
    }

    public function kyc()
    {
        return $this->hasOne(Kyc::class, 'id_company');
    }

    public function finance()
    {
        return $this->hasOne(Finance::class,'company_id');
    }

    public function transfer_manual()
    {
        return $this->hasOne(ManualTransfer::class,'company_id');
    }
    public function theme()
    {
        return $this->hasMany('\App\Models\CompanyTheme', 'id_company', 'id_company');
    }

    public function order()
    {
        return $this->hasMany('\App\Models\Order', 'id_company', 'id_company');
    }

    public function paid_order()
    {
        return $this->hasMany('\App\Models\Order', 'id_company', 'id_company')->where('status',1)->sum('total_amount');
    }

    public function bank()
    {
        return $this->hasOne('\App\Models\CompanyBank', 'id_company', 'id_company')->where('status', 1)->orderBy('id','desc');
    }
    
    public function wallet()
    {
        return $this->hasMany('\App\Models\CompanyWallet', 'id_company', 'id_company');
    }

    public function email_server()
    {
        return $this->hasOne('\App\Models\EmailServer', 'id_company', 'id_company');
    }

    public function getActiveThemeAttribute()
    {
        $theme = $this->theme()->where('status', 1)->first();
        return $theme;
    }

    public function getLogoUrlAttribute()
    {
        $logo = $this->attributes['logo'];
        if ($logo != "") {
            if (count(explode('/', $logo)) > 1) {
                $logo = $logo;
            } else {
                $logo = 'uploads/company_logo/'.$logo;
            }
        }
//        if (\File::exists( $logo)) {
        return asset($logo);
//        }

//        return asset('img/no-product-image.png');
    }

    public function getBannerUrlAttribute()
    {
        $logo = $this->attributes['banner'];

        if (\File::exists('uploads/banners/'.$logo)) {
            return asset('uploads/banners/'.$logo);
        }
        if (\File::exists($logo)) {
            return asset($logo);
        }

        return asset('img/no-banner-image.png');
    }

    public function categories()
    {
        return $this->belongsToMany(BusinessCategory::class, 'business_category_company', 'company_id',
                'business_category_id');
    }

    public function gpx_journals()
    {
        return $this->hasMany(JournalGXP::class, 'id_company');
    }

    public function cash_backs()
    {
        return $this->hasMany(CashBackVoucher::class, 'id_company');
    }

    public function updates()
    {
        return $this->belongsToMany(Update::class, 'company_update', 'id_company', 'id_update')->withPivot('read_at');
    }

    public function achievement_details()
    {
        return $this->belongsToMany(AchievementDetail::class, 'achievement_detail_company', 'company_id',
                'achievement_id')->withPivot('achievement_status');
    }

    public function achievements()
    {
//        $achievements = Achievement::has('details')->get();
//        $company = $this;
//        return $achievements->map(function ($model) use ($company) {
//            $array = [
//                    'id' => $model->id,
//                    'icon' => asset($model->icon),
//                    'title_en' => $model->title_en,
//                    'title_id' => $model->title_id,
//                    'description_en' => $model->description_en,
//                    'description_id' => $model->description_id,
//                    'reward_point'=>$model->reward_point,
//                    'status'=>AchievementDetail::whereHas('achievement', function ($a) use($model){
//                        $a->where('id',$model->id);
//                    })->whereHas('companies', function ($c){
//                        $c->where('id_company',52)->where('achievement_detail_company.achievement_status',0);
//                    })->count()===0?true:false
//            ];
//
//            return $array;
//        });

        return $this->belongsToMany(Achievement::class, 'company_achievement', 'company_id',
            'achievement_id')->withPivot('company_achievement_status');
    }

    public function scopeKlhk($query)
    {
        return $query->where('is_klhk',1);
    }

    public function google_review_widget()
    {
        return $this->hasOne(GoogleReviewWidget::class,'company_id');
    }

    public function payments()
    {
        return $this->belongsToMany(ListPayment::class, 'company_payment', 'company_id', 'payment_id')->withPivot(['charge_to']);
    }

    public function distribution_requests()
    {
        return $this->hasMany(DistributionRequest::class,'company_id');
    }
    public function insurance_requests()
    {
        return $this->hasMany(InsuranceRequest::class,'company_id');
    }

}
