<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomDetail extends Model
{
    protected $table = 'custom_detail';

    protected $fillable = [
        'order_detail_id',
        'value',
        'label_name',
        'type_custom',
        'custom_schema_id',
        'participant'
    ];

    public function customSchema()
    {
        return $this->hasOne(CustomSchema::class, 'id', 'custom_schema_id');
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->attributes['value'] = $value;
    }

    public function getValueAttribute($value)
    {
        switch($this->type_custom) {
            case 'photo':
            case 'document':
                return url('storage/'.str_replace('public/', '', $value));
                break;
            case 'checkbox':
                return json_decode($value, true);
                break;
            default:
                return $value;
                break;
        }

        
    }
}
