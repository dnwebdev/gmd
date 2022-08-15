<?php

namespace App\Mail\Bank;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BankAccountChangeRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    private $bank;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bank,$user)
    {
        //
        $this->bank = $bank;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['bank'] = $this->bank;
        $data['user'] = $this->user;
        $bankName = null;
        if ($this->bank->bank === 'BCA') {
            $bankName = 'Bank Central Asia (BCA)';
        } elseif ($this->bank->bank === 'DANAMON') {
            $bankName = 'Bank Danamon';
        } elseif ($this->bank->bank === 'ARTHA') {
            $bankName = 'Bank Artha Graha International';
        } elseif ($this->bank->bank === 'ANZ') {
            $bankName = 'Bank ANZ Indonesia';
        } elseif ($this->bank->bank === 'BJB') {
            $bankName = 'Bank BJB';
        } elseif ($this->bank->bank === 'BJB_SYR') {
            $bankName = 'Bank BJB Syariah';
        } elseif ($this->bank->bank === 'PERMATA') {
            $bankName = 'Bank Permata';
        } elseif ($this->bank->bank === 'PANIN') {
            $bankName = 'Bank Panin';
        } elseif ($this->bank->bank === 'CAPITAL') {
            $bankName = 'Bank Capital Indonesia';
        } elseif ($this->bank->bank === 'ARTOS') {
            $bankName = 'Bank Artos Indonesia';
        } elseif ($this->bank->bank === 'BUMI_ARTA') {
            $bankName = 'Bank Bumi Arta';
        } elseif ($this->bank->bank === 'BNI_SYR') {
            $bankName = 'Bank BNI Syariah';
        } elseif ($this->bank->bank === 'BUKOPIN') {
            $bankName = 'Bank Bukopin';
        } elseif ($this->bank->bank === 'AGRONIAGA') {
            $bankName = 'Bank BRI Agroniaga';
        } elseif ($this->bank->bank === 'MANDIRI') {
            $bankName = 'Bank Mandiri';
        } elseif ($this->bank->bank === 'AGRIS') {
            $bankName = 'Bank Agris';
        } elseif ($this->bank->bank === 'CIMB') {
            $bankName = 'Bank CIMB Niaga';
        } elseif ($this->bank->bank === 'SINARMAS') {
            $bankName = 'Bank Sinarmas';
        } elseif ($this->bank->bank === 'BANGKOK') {
            $bankName = 'Bangkok Bank';
        } elseif ($this->bank->bank === 'BISNIS_INTERNASIONAL') {
            $bankName = 'Bank Bisnis Internasional';
        } elseif ($this->bank->bank === 'ANGLOMAS') {
            $bankName = 'Anglomas International Bank';
        } elseif ($this->bank->bank === 'ANDARA') {
            $bankName = 'Bank Andara';
        } elseif ($this->bank->bank === 'BNP_PARIBAS') {
            $bankName = 'Bank BNP Paribas';
        } elseif ($this->bank->bank === 'COMMONWEALTH') {
            $bankName = 'Bank Commonwealth';
        } elseif ($this->bank->bank === 'BCA_SYR') {
            $bankName = 'Bank Central Asia (BCA) Syariah';
        } elseif ($this->bank->bank === 'DANAMON_UUS') {
            $bankName = 'Bank Danamon UUS';
        } elseif ($this->bank->bank === 'INA_PERDANA') {
            $bankName = 'Bank Ina Perdania';
        } elseif ($this->bank->bank === 'DKI') {
            $bankName = 'Bank DKI';
        } elseif ($this->bank->bank === 'GANESHA') {
            $bankName = 'Bank Ganesha';
        } elseif ($this->bank->bank === 'CHINATRUST') {
            $bankName = 'Bank Chinatrust Indonesia';
        } elseif ($this->bank->bank === 'HANA') {
            $bankName = 'Bank Hana';
        } elseif ($this->bank->bank === 'DINAR_INDONESIA') {
            $bankName = 'Bank Dinar Indonesia';
        } elseif ($this->bank->bank === 'CIMB_UUS') {
            $bankName = 'Bank CIMB Niaga UUS';
        } elseif ($this->bank->bank === 'DKI_UUS') {
            $bankName = 'Bank DKI UUS';
        } elseif ($this->bank->bank === 'FAMA') {
            $bankName = 'Bank Fama International';
        } elseif ($this->bank->bank === 'HIMPUNAN_SAUDARA') {
            $bankName = 'Bank Himpunan Saudara 1906';
        } elseif ($this->bank->bank === 'ICBC') {
            $bankName = 'Bank ICBC Indonesia';
        } elseif ($this->bank->bank === 'HARDA_INTERNASIONAL') {
            $bankName = 'Bank Harda Internasional';
        } elseif ($this->bank->bank === 'DBS') {
            $bankName = 'Bank DBS Indonesia';
        } elseif ($this->bank->bank === 'INDEX_SELINDO') {
            $bankName = 'Bank Index Selindo';
        } elseif ($this->bank->bank === 'PANIN_SYR') {
            $bankName = 'Bank Panin Syariah';
        } elseif ($this->bank->bank === 'JAWA_TENGAH_UUS') {
            $bankName = 'BPD Jawa Tengah UUS';
        } elseif ($this->bank->bank === 'KALIMANTAN_TIMUR_UUS') {
            $bankName = 'BPD Kalimantan Timur UUS';
        } elseif ($this->bank->bank === 'BTN_UUS') {
            $bankName = 'Bank Tabungan Negara (BTN) UUS';
        } elseif ($this->bank->bank === 'ACEH_UUS') {
            $bankName = 'BPD Aceh UUS';
        } elseif ($this->bank->bank === 'KALIMANTAN_BARAT_UUS') {
            $bankName = 'BPD Kalimantan Barat UUS';
        } elseif ($this->bank->bank === 'JASA_JAKARTA') {
            $bankName = 'Bank Jasa Jakarta';
        } elseif ($this->bank->bank === 'DAERAH_ISTIMEWA') {
            $bankName = 'BPD Daerah Istimewa Yogyakarta (DIY)';
        } elseif ($this->bank->bank === 'KALIMANTAN_BARAT') {
            $bankName = 'BPD Kalimantan Barat';
        } elseif ($this->bank->bank === 'MASPION') {
            $bankName = 'Bank Maspion Indonesia';
        } elseif ($this->bank->bank === 'MAYAPADA') {
            $bankName = 'Bank Mayapada International';
        } elseif ($this->bank->bank === 'BRI_SYR') {
            $bankName = 'Bank Syariah BRI';
        } elseif ($this->bank->bank === 'TABUNGAN_PENSIUNAN_NASIONAL') {
            $bankName = 'Bank Tabungan Pensiunan Nasional';
        } elseif ($this->bank->bank === 'VICTORIA_INTERNASIONAL') {
            $bankName = 'Bank Victoria Internasional';
        } elseif ($this->bank->bank === 'BALI') {
            $bankName = 'BPD Bali';
        } elseif ($this->bank->bank === 'JAWA_TENGAH') {
            $bankName = 'BPD Jawa Tengah';
        } elseif ($this->bank->bank === 'KALIMANTAN_SELATAN') {
            $bankName = 'BPD Kalimantan Selatan';
        } elseif ($this->bank->bank === 'MAYBANK_SYR') {
            $bankName = 'Bank Maybank Syariah Indonesia';
        } elseif ($this->bank->bank === 'SAHABAT_SAMPOERNA') {
            $bankName = 'Bank Sahabat Sampoerna';
        } elseif ($this->bank->bank === 'KALIMANTAN_SELATAN_UUS') {
            $bankName = 'BPD Kalimantan Selatan UUS';
        } elseif ($this->bank->bank === 'KALIMANTAN_TENGAH') {
            $bankName = 'BPD Kalimantan Tengah';
        } elseif ($this->bank->bank === 'MUAMALAT') {
            $bankName = 'Bank Muamalat Indonesia';
        } elseif ($this->bank->bank === 'BUKOPIN_SYR') {
            $bankName = 'Bank Syariah Bukopin';
        } elseif ($this->bank->bank === 'NUSANTARA_PARAHYANGAN') {
            $bankName = 'Bank Nusantara Parahyangan';
        } elseif ($this->bank->bank === 'JAMBI_UUS') {
            $bankName = 'BPD Jambi UUS';
        } elseif ($this->bank->bank === 'JAWA_TIMUR') {
            $bankName = 'BPD Jawa Timur';
        } elseif ($this->bank->bank === 'MEGA') {
            $bankName = 'Bank Mega';
        } elseif ($this->bank->bank === 'DAERAH_ISTIMEWA_UUS') {
            $bankName = 'BPD Daerah Istimewa Yogyakarta (DIY) UUS';
        } elseif ($this->bank->bank === 'KALIMANTAN_TIMUR') {
            $bankName = 'BPD Kalimantan Timur';
        } elseif ($this->bank->bank === 'MULTI_ARTA_SENTOSA') {
            $bankName = 'Bank Multi Arta Sentosa';
        } elseif ($this->bank->bank === 'OCBC') {
            $bankName = 'Bank OCBC NISP';
        } elseif ($this->bank->bank === 'NATIONALNOBU') {
            $bankName = 'Bank Nationalnobu';
        } elseif ($this->bank->bank === 'BOC') {
            $bankName = 'Bank of China (BOC)';
        } elseif ($this->bank->bank === 'BTN') {
            $bankName = 'Bank Tabungan Negara (BTN)';
        } elseif ($this->bank->bank === 'BENGKULU') {
            $bankName = 'BPD Bengkulu';
        } elseif ($this->bank->bank === 'RESONA') {
            $bankName = 'Bank Resona Perdania';
        } elseif ($this->bank->bank === 'MANDIRI_SYR') {
            $bankName = 'Bank Syariah Mandiri';
        } elseif ($this->bank->bank === 'WOORI') {
            $bankName = 'Bank Woori Indonesia';
        } elseif ($this->bank->bank === 'YUDHA_BHAKTI') {
            $bankName = 'Bank Yudha Bhakti';
        } elseif ($this->bank->bank === 'ACEH') {
            $bankName = 'BPD Aceh';
        } elseif ($this->bank->bank === 'MAYORA') {
            $bankName = 'Bank Mayora';
        } elseif ($this->bank->bank === 'BAML') {
            $bankName = 'Bank of America Merill-Lynch';
        } elseif ($this->bank->bank === 'PERMATA_UUS') {
            $bankName = 'Bank Permata UUS';
        } elseif ($this->bank->bank === 'KESEJAHTERAAN_EKONOMI') {
            $bankName = 'Bank Kesejahteraan Ekonomi';
        } elseif ($this->bank->bank === 'MESTIKA_DHARMA') {
            $bankName = 'Bank Mestika Dharma';
        } elseif ($this->bank->bank === 'OCBC_UUS') {
            $bankName = 'Bank OCBC NISP UUS';
        } elseif ($this->bank->bank === 'RABOBANK') {
            $bankName = 'Bank Rabobank International Indonesia';
        } elseif ($this->bank->bank === 'ROYAL') {
            $bankName = 'Bank Royal Indonesia';
        } elseif ($this->bank->bank === 'MITSUI') {
            $bankName = 'Bank Sumitomo Mitsui Indonesia';
        } elseif ($this->bank->bank === 'UOB') {
            $bankName = 'Bank UOB Indonesia';
        } elseif ($this->bank->bank === 'INDIA') {
            $bankName = 'Bank of India Indonesia';
        } elseif ($this->bank->bank === 'SBI_INDONESIA') {
            $bankName = 'Bank SBI Indonesia';
        } elseif ($this->bank->bank === 'MEGA_SYR') {
            $bankName = 'Bank Syariah Mega';
        } elseif ($this->bank->bank === 'JAMBI') {
            $bankName = 'BPD Jambi';
        } elseif ($this->bank->bank === 'JAWA_TIMUR_UUS') {
            $bankName = 'BPD Jawa Timur UUS';
        } elseif ($this->bank->bank === 'MIZUHO') {
            $bankName = 'Bank Mizuho Indonesia';
        } elseif ($this->bank->bank === 'MNC_INTERNASIONAL') {
            $bankName = 'Bank MNC Internasional';
        } elseif ($this->bank->bank === 'TOKYO') {
            $bankName = 'Bank of Tokyo Mitsubishi UFJ';
        } elseif ($this->bank->bank === 'VICTORIA_SYR') {
            $bankName = 'Bank Victoria Syariah';
        } elseif ($this->bank->bank === 'LAMPUNG') {
            $bankName = 'BPD Lampung';
        } elseif ($this->bank->bank === 'MALUKU') {
            $bankName = 'BPD Maluku';
        } elseif ($this->bank->bank === 'SUMSEL_DAN_BABEL_UUS') {
            $bankName = 'BPD Sumsel Dan Babel UUS';
        } elseif ($this->bank->bank === 'MAYBANK') {
            $bankName = 'Bank Maybank';
        } elseif ($this->bank->bank === 'JPMORGAN') {
            $bankName = 'JP Morgan Chase Bank';
        } elseif ($this->bank->bank === 'SULSELBAR_UUS') {
            $bankName = 'BPD Sulselbar UUS';
        } elseif ($this->bank->bank === 'SULAWESI_TENGGARA') {
            $bankName = 'BPD Sulawesi Tenggara';
        } elseif ($this->bank->bank === 'NUSA_TENGGARA_BARAT') {
            $bankName = 'BPD Nusa Tenggara Barat';
        } elseif ($this->bank->bank === 'RIAU_DAN_KEPRI_UUS') {
            $bankName = 'BPD Riau Dan Kepri UUS';
        } elseif ($this->bank->bank === 'SULUT') {
            $bankName = 'BPD Sulut';
        } elseif ($this->bank->bank === 'SUMUT') {
            $bankName = 'BPD Sumut';
        } elseif ($this->bank->bank === 'DEUTSCHE') {
            $bankName = 'Deutsche Bank';
        } elseif ($this->bank->bank === 'STANDARD_CHARTERED') {
            $bankName = 'Standard Charted Bank';
        } elseif ($this->bank->bank === 'BRI') {
            $bankName = 'Bank Rakyat Indonesia (BRI)';
        } elseif ($this->bank->bank === 'HSBC') {
            $bankName = 'HSBC Indonesia (formerly Bank Ekonomi Raharja)';
        } elseif ($this->bank->bank === 'SULSELBAR') {
            $bankName = 'BPD Sulselbar';
        } elseif ($this->bank->bank === 'SUMATERA_BARAT_UUS') {
            $bankName = 'BPD Sumatera Barat UUS';
        } elseif ($this->bank->bank === 'NUSA_TENGGARA_BARAT_UUS') {
            $bankName = 'BPD Nusa Tenggara Barat UUS';
        } elseif ($this->bank->bank === 'HSBC_UUS') {
            $bankName = 'Hongkong and Shanghai Bank Corporation (HSBC) UUS';
        } elseif ($this->bank->bank === 'PAPUA') {
            $bankName = 'BPD Papua';
        } elseif ($this->bank->bank === 'SULAWESI') {
            $bankName = 'BPD Sulawesi Tengah';
        } elseif ($this->bank->bank === 'SUMATERA_BARAT') {
            $bankName = 'BPD Sumatera Barat';
        } elseif ($this->bank->bank === 'SUMUT_UUS') {
            $bankName = 'BPD Sumut UUS';
        } elseif ($this->bank->bank === 'BNI') {
            $bankName = 'Bank Negara Indonesia (BNI)';
        } elseif ($this->bank->bank === 'PRIMA_MASTER') {
            $bankName = 'Prima Master Bank';
        } elseif ($this->bank->bank === 'MITRA_NIAGA') {
            $bankName = 'Bank Mitra Niaga';
        } elseif ($this->bank->bank === 'NUSA_TENGGARA_TIMUR') {
            $bankName = 'BPD Nusa Tenggara Timur';
        } elseif ($this->bank->bank === 'SUMSEL_DAN_BABEL') {
            $bankName = 'BPD Sumsel Dan Babel';
        } elseif ($this->bank->bank === 'RBS') {
            $bankName = 'Royal Bank of Scotland (RBS)';
        } elseif ($this->bank->bank === 'ARTA_NIAGA_KENCANA') {
            $bankName = 'Bank Arta Niaga Kencana';
        } elseif ($this->bank->bank === 'CITIBANK') {
            $bankName = 'Citibank';
        } elseif ($this->bank->bank === 'RIAU_DAN_KEPRI') {
            $bankName = 'BPD Riau Dan Kepri';
        } elseif ($this->bank->bank === 'CENTRATAMA') {
            $bankName = 'Centratama Nasional Bank';
        } elseif ($this->bank->bank === 'OKE') {
            $bankName = 'Bank Oke Indonesia (formerly Bank Andara)';
        } elseif ($this->bank->bank === 'MANDIRI_ECASH') {
            $bankName = 'Mandiri E-Cash';
        } elseif ($this->bank->bank === 'AMAR') {
            $bankName = 'Bank Amar Indonesia (formerly Anglomas International Bank)';
        } elseif ($this->bank->bank === 'GOPAY') {
            $bankName = 'GoPay';
        } elseif ($this->bank->bank === 'SINARMAS_UUS') {
            $bankName = 'Bank Sinarmas UUS';
        } elseif ($this->bank->bank === 'OVO') {
            $bankName = 'OVO';
        } elseif ($this->bank->bank === 'EXIMBANK') {
            $bankName = 'Indonesia Eximbank (formerly Bank Ekspor Indonesia)';
        } elseif ($this->bank->bank === 'JTRUST') {
            $bankName = 'Bank JTrust Indonesia (formerly Bank Mutiara)';
        } elseif ($this->bank->bank === 'WOORI_SAUDARA') {
            $bankName = 'Bank Woori Saudara Indonesia 1906 (formerly Bank Himpunan Saudara and Bank Woori Indonesia)';
        } elseif ($this->bank->bank === 'BTPN_SYARIAH') {
            $bankName = 'BTPN Syariah (formerly BTPN UUS and Bank Sahabat Purba Danarta)';
        } elseif ($this->bank->bank === 'SHINHAN') {
            $bankName = 'Bank Shinhan Indonesia (formerly Bank Metro Express)';
        } elseif ($this->bank->bank === 'BANTEN') {
            $bankName = 'BPD Banten (formerly Bank Pundi Indonesia)';
        } elseif ($this->bank->bank === 'CCB') {
            $bankName = 'China Construction Bank Indonesia (formerly Bank Antar Daerah and Bank Windu Kentjana International)';
        } elseif ($this->bank->bank === 'MANDIRI_TASPEN') {
            $bankName = 'Mandiri Taspen Pos (formerly Bank Sinar Harapan Bali)';
        } elseif ($this->bank->bank === 'QNB_INDONESIA') {
            $bankName = 'Bank QNB Indonesia (formerly Bank QNB Kesawan)';
        }
        $data['bankName'] = $bankName;

        return $this->from('support@'.env('APP_URL'))->subject('Change Bank Account')->view('mail.bank.change-bank-account-request',$data);
    }
}
