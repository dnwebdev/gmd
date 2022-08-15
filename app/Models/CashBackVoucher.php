<?php
/**
 * Created by PhpStorm.
 * User: hkari
 * Date: 3/26/2019
 * Time: 12:43 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CashBackVoucher extends Model
{

    protected $table = 'tbl_voucher_cashback';
    protected  $primaryKey = 'id';
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class,'id_company');
    }
}