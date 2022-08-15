<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBank extends Model
{
    protected $table = 'tbl_company_bank_account';
    protected $guarded = ['id'];
    protected $appends = ['bank_name'];

    public static $banks = [
        'BRI' => 'Bank Rakyat Indonesia (BRI)',
        'BCA' => 'Bank Central Asia (BCA)',
        'BNI' => 'Bank Negara Indonesia (BNI)',
        'MANDIRI' => 'Bank Mandiri',
        'DANAMON' => 'Bank Danamon',
        'CIMB' => 'Bank CIMB Niaga',
        'ARTHA' => 'Bank Artha Graha International',
        'ANZ' => 'Bank ANZ Indonesia',
        'BJB' => 'Bank BJB',
        'BJB_SYR' => 'Bank BJB Syariah',
        'PERMATA' => 'Bank Permata',
        'PANIN' => 'Bank Panin',
        'CAPITAL' => 'Bank Capital Indonesia',
        'ARTOS' => 'Bank Artos Indonesia',
        'BUMI_ARTA' => 'Bank Bumi Arta',
        'BNI_SYR' => 'Bank BNI Syariah',
        'BUKOPIN' => 'Bank Bukopin',
        'AGRONIAGA' => 'Bank BRI Agroniaga',
        'AGRIS' => 'Bank Agris',
        'SINARMAS' => 'Bank Sinarmas',
        'BANGKOK' => 'Bangkok Bank',
        'BISNIS_INTERNASIONAL' => 'Bank Bisnis Internasional',
        'ANGLOMAS' => 'Anglomas International Bank',
        'ANDARA' => 'Bank Andara',
        'BNP_PARIBAS' => 'Bank BNP Paribas',
        'COMMONWEALTH' => 'Bank Commonwealth',
        'BCA_SYR' => 'Bank Central Asia (BCA) Syariah',
        'DANAMON_UUS' => 'Bank Danamon UUS',
        'INA_PERDANA' => 'Bank Ina Perdania',
        'DKI' => 'Bank DKI',
        'GANESHA' => 'Bank Ganesha',
        'CHINATRUST' => 'Bank Chinatrust Indonesia',
        'HANA' => 'Bank Hana',
        'DINAR_INDONESIA' => 'Bank Dinar Indonesia',
        'CIMB_UUS' => 'Bank CIMB Niaga UUS',
        'DKI_UUS' => 'Bank DKI UUS',
        'FAMA' => 'Bank Fama International',
        'HIMPUNAN_SAUDARA' => 'Bank Himpunan Saudara 1906',
        'ICBC' => 'Bank ICBC Indonesia',
        'HARDA_INTERNASIONAL' => 'Bank Harda Internasional',
        'DBS' => 'Bank DBS Indonesia',
        'INDEX_SELINDO' => 'Bank Index Selindo',
        'PANIN_SYR' => 'Bank Panin Syariah',
        'JAWA_TENGAH_UUS' => 'BPD Jawa Tengah UUS',
        'KALIMANTAN_TIMUR_UUS' => 'BPD Kalimantan Timur UUS',
        'BTN_UUS' => 'Bank Tabungan Negara (BTN) UUS',
        'ACEH_UUS' => 'BPD Aceh UUS',
        'KALIMANTAN_BARAT_UUS' => 'BPD Kalimantan Barat UUS',
        'JASA_JAKARTA' => 'Bank Jasa Jakarta',
        'DAERAH_ISTIMEWA' => 'BPD Daerah Istimewa Yogyakarta (DIY)',
        'KALIMANTAN_BARAT' => 'BPD Kalimantan Barat',
        'MASPION' => 'Bank Maspion Indonesia',
        'MAYAPADA' => 'Bank Mayapada International',
        'BRI_SYR' => 'Bank Syariah BRI',
        'TABUNGAN_PENSIUNAN_NASIONAL' => 'Bank Tabungan Pensiunan Nasional',
        'VICTORIA_INTERNASIONAL' => 'Bank Victoria Internasional',
        'BALI' => 'BPD Bali',
        'JAWA_TENGAH' => 'BPD Jawa Tengah',
        'KALIMANTAN_SELATAN' => 'BPD Kalimantan Selatan',
        'MAYBANK_SYR' => 'Bank Maybank Syariah Indonesia',
        'SAHABAT_SAMPOERNA' => 'Bank Sahabat Sampoerna',
        'KALIMANTAN_SELATAN_UUS' => 'BPD Kalimantan Selatan UUS',
        'KALIMANTAN_TENGAH' => 'BPD Kalimantan Tengah',
        'MUAMALAT' => 'Bank Muamalat Indonesia',
        'BUKOPIN_SYR' => 'Bank Syariah Bukopin',
        'NUSANTARA_PARAHYANGAN' => 'Bank Nusantara Parahyangan',
        'JAMBI_UUS' => 'BPD Jambi UUS',
        'JAWA_TIMUR' => 'BPD Jawa Timur',
        'MEGA' => 'Bank Mega',
        'DAERAH_ISTIMEWA_UUS' => 'BPD Daerah Istimewa Yogyakarta (DIY) UUS',
        'KALIMANTAN_TIMUR' => 'BPD Kalimantan Timur',
        'MULTI_ARTA_SENTOSA' => 'Bank Multi Arta Sentosa',
        'OCBC' => 'Bank OCBC NISP',
        'NATIONALNOBU' => 'Bank Nationalnobu',
        'BOC' => 'Bank of China (BOC)',
        'BTN' => 'Bank Tabungan Negara (BTN)',
        'BENGKULU' => 'BPD Bengkulu',
        'RESONA' => 'Bank Resona Perdania',
        'MANDIRI_SYR' => 'Bank Syariah Mandiri',
        'WOORI' => 'Bank Woori Indonesia',
        'YUDHA_BHAKTI' => 'Bank Yudha Bhakti',
        'ACEH' => 'BPD Aceh',
        'MAYORA' => 'Bank Mayora',
        'BAML' => 'Bank of America Merill-Lynch',
        'PERMATA_UUS' => 'Bank Permata UUS',
        'KESEJAHTERAAN_EKONOMI' => 'Bank Kesejahteraan Ekonomi',
        'MESTIKA_DHARMA' => 'Bank Mestika Dharma',
        'OCBC_UUS' => 'Bank OCBC NISP UUS',
        'RABOBANK' => 'Bank Rabobank International Indonesia',
        'ROYAL' => 'Bank Royal Indonesia',
        'MITSUI' => 'Bank Sumitomo Mitsui Indonesia',
        'UOB' => 'Bank UOB Indonesia',
        'INDIA' => 'Bank of India Indonesia',
        'SBI_INDONESIA' => 'Bank SBI Indonesia',
        'MEGA_SYR' => 'Bank Syariah Mega',
        'JAMBI' => 'BPD Jambi',
        'JAWA_TIMUR_UUS' => 'BPD Jawa Timur UUS',
        'MIZUHO' => 'Bank Mizuho Indonesia',
        'MNC_INTERNASIONAL' => 'Bank MNC Internasional',
        'TOKYO' => 'Bank of Tokyo Mitsubishi UFJ',
        'VICTORIA_SYR' => 'Bank Victoria Syariah',
        'LAMPUNG' => 'BPD Lampung',
        'MALUKU' => 'BPD Maluku',
        'SUMSEL_DAN_BABEL_UUS' => 'BPD Sumsel Dan Babel UUS',
        'MAYBANK' => 'Bank Maybank',
        'JPMORGAN' => 'JP Morgan Chase Bank',
        'SULSELBAR_UUS' => 'BPD Sulselbar UUS',
        'SULAWESI_TENGGARA' => 'BPD Sulawesi Tenggara',
        'NUSA_TENGGARA_BARAT' => 'BPD Nusa Tenggara Barat',
        'RIAU_DAN_KEPRI_UUS' => 'BPD Riau Dan Kepri UUS',
        'SULUT' => 'BPD Sulut',
        'SUMUT' => 'BPD Sumut',
        'DEUTSCHE' => 'Deutsche Bank',
        'STANDARD_CHARTERED' => 'Standard Charted Bank',
        'HSBC' => 'HSBC Indonesia (formerly Bank Ekonomi Raharja)',
        'SULSELBAR' => 'BPD Sulselbar',
        'SUMATERA_BARAT_UUS' => 'BPD Sumatera Barat UUS',
        'NUSA_TENGGARA_BARAT_UUS' => 'BPD Nusa Tenggara Barat UUS',
        'HSBC_UUS' => 'Hongkong and Shanghai Bank Corporation (HSBC) UUS',
        'PAPUA' => 'BPD Papua',
        'SULAWESI' => 'BPD Sulawesi Tengah',
        'SUMATERA_BARAT' => 'BPD Sumatera Barat',
        'SUMUT_UUS' => 'BPD Sumut UUS',
        'PRIMA_MASTER' => 'Prima Master Bank',
        'MITRA_NIAGA' => 'Bank Mitra Niaga',
        'NUSA_TENGGARA_TIMUR' => 'BPD Nusa Tenggara Timur',
        'SUMSEL_DAN_BABEL' => 'BPD Sumsel Dan Babel',
        'RBS' => 'Royal Bank of Scotland (RBS)',
        'ARTA_NIAGA_KENCANA' => 'Bank Arta Niaga Kencana',
        'CITIBANK' => 'Citibank',
        'RIAU_DAN_KEPRI' => 'BPD Riau Dan Kepri',
        'CENTRATAMA' => 'Centratama Nasional Bank',
        'OKE' => 'Bank Oke Indonesia (formerly Bank Andara)',
        'MANDIRI_ECASH' => 'Mandiri E-Cash',
        'AMAR' => 'Bank Amar Indonesia (formerly Anglomas International Bank)',
        'GOPAY' => 'GoPay',
        'SINARMAS_UUS' => 'Bank Sinarmas UUS',
        'OVO' => 'OVO',
        'EXIMBANK' => 'Indonesia Eximbank (formerly Bank Ekspor Indonesia)',
        'JTRUST' => 'Bank JTrust Indonesia (formerly Bank Mutiara)',
        'WOORI_SAUDARA' => 'Bank Woori Saudara Indonesia 1906 (formerly Bank Himpunan Saudara and Bank Woori Indonesia)',
        'BTPN_SYARIAH' => 'BTPN Syariah (formerly BTPN UUS and Bank Sahabat Purba Danarta)',
        'SHINHAN' => 'Bank Shinhan Indonesia (formerly Bank Metro Express)',
        'BANTEN' => 'BPD Banten (formerly Bank Pundi Indonesia)',
        'CCB' => 'China Construction Bank Indonesia (formerly Bank Antar Daerah and Bank Windu Kentjana International)',
        'MANDIRI_TASPEN' => 'Mandiri Taspen Pos (formerly Bank Sinar Harapan Bali)',
        'QNB_INDONESIA' => 'Bank QNB Indonesia (formerly Bank QNB Kesawan)'
    ];
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::created(function ($model){
            updateAchievement($model->company);
        });
    }

    public function company(){
    	return $this->belongsTo('App\Models\Company','id_company','id_company');
    }

    public function request_changes()
    {
        return $this->hasMany(ChangeBankRequest::class,'id_company_bank');
    }
    public function checkRequest()
    {
        return $this->hasOne(ChangeBankRequest::class, 'id_company_bank');
    }

    public function getBankNameAttribute()
    {
        if (!isset(self::$banks[$this->bank])) {
            return null;
        }

        return self::$banks[$this->bank];
    }
}
