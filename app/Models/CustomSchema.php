<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomSchema extends Model
{
    use SoftDeletes;

    protected $table = 'custom_schema';

    protected $fillable = [
        'product_id',
        'type_custom',
        'label_name',
        'description',
        'fill_type',
        'value'
    ];

    public static $types = [
        'text'      => 'Input Teks',
        'textarea'  => 'Input Textarea',
        'number'    => 'Input Nomor',
        'choices'   => 'Input Pilihan',
        'checkbox'  => 'Input Checklist',
        'document'  => 'Upload Dokumen (PDF, TXT)',
        'photo'     => 'Upload Foto',
        'dropdown'  => 'Dropdown',
        'country'   => 'Negara',
        'state'     => 'Provinsi',
        'city'      => 'Kota'
    ];

    public static $fill_types = [
        'customer'            => 'Pemesan',
        'all participant'     => 'Semua Peserta'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
    ];

    public function setValueAttribute($value)
    {
        if (empty($value)) $value = [null];

        $this->attributes['value'] = json_encode($value);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
