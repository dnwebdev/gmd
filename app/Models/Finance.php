<?php


namespace App\Models;

use App\Scopes\ActiveProviderScope;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Finance extends Model
{
    protected $table = 'finance';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    public function verification()
    {
        return $this->hasOne(VerificationFinance::class,'finance_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id')->withoutGlobalScope(ActiveProviderScope::class);
    }

    public function typeFinance()
    {
        return $this->belongsTo(TypeFinance::class, 'type_finance_id');
    }

    public function timeFinance()
    {
        return $this->belongsTo(TimeFinance::class, 'time_finance_id');
    }
}
