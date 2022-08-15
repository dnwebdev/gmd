<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class UserAgent extends Authenticatable
{
    use Notifiable,HasApiTokens;
    
    protected $table = 'tbl_user_agent';
    protected $fillable = ['id_company','first_name','last_name','email','phone','password','language','status','phone_code','api_token'];
    protected $hidden = ['password', 'remember_token'];
    protected $primaryKey = 'id_user_agen';

    public function company()
    {
        return $this->belongsTo('App\Models\Company','id_company','id_company');
    }

    public function created_by()
    {
        return $this->belongsTo(UserAgent::class, 'id_user_agen');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MyResetPasswordNotification($token));
    }
}
