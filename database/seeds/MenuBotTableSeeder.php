<?php

use App\Models\Inbox;
use App\Models\MenuBot;
use App\Models\MenuKeyword;
use App\Models\RawInbox;
use App\Models\WoowaContact;
use Illuminate\Database\Seeder;

class MenuBotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inbox::query()->delete();
        RawInbox::query()->delete();
        MenuKeyword::query()->delete();
        MenuBot::query()->delete();
        WoowaContact::query()->delete();

        $mainMenu = new MenuBot();
        $mainMenu->slug = 'main';
        $reply[] = "Selamat datang di layanan digital Gomodo. Saya Gomi yang siap membantu Anda :-)";
        $reply[] = "Silahkan ketik bantuan yang Anda inginkan, atau ketik Kontak Gomodo  untuk berbicara dengan Customer Service kami.";
        $reply[] = " *1. Kontak Gomodo*";
        $reply[] = " *2. Tentang Gomodo*";
        $mainMenu->content = sprintf('%s', implode("\n", $reply));
        $mainMenu->save();

        $contactMenu = new MenuBot();
        $contactMenu->parent_id = $mainMenu->id;
        $contactMenu->slug = 'contact-cs';
        $contactMenu->content = "Baik, saya akan sambungkan dengan customer service kami untuk segera menghubungi Anda";
        $contactMenu->save();
        $contactMenu->keywords()->createMany([
            [
                'keyword' => '1',
            ],
            [
                'keyword' => 'kontak'
            ],
            [
                'keyword' => 'kontak gomodo'
            ]
        ]);


        $aboutMenu = new MenuBot();
        $aboutMenu->parent_id = $mainMenu->id;
        $aboutMenu->slug = 'about-gomodo';
        $reply = [];
        $reply[] = "Gomodo menyediakan teknologi perangkat lunak perusahaan untuk bisnis di sektor jasa dan wisata.";
        $reply[] = "Kami memberdayakan klien UKM kami secara digital dengan situs web gratis untuk menerima booking dan pembayaran non-tunai, faktur elektronik, solusi pemasaran digital, sistem distribusi terintegrasi, platform pendanaan usaha, dan banyak lagi. Semuanya ada di platform yang mudah digunakan bahkan di smartphone.";
        $reply[] = "Jika Anda adalah penggiat bisnis jasa wisata ataupun jasa non-wisata, dan ingin mengupdate dapat mendaftar disini: https://gomodo.id/register-provider";
        $reply[] = "";
        $reply[] = "Jika Anda sudah terdaftar di Gomodo:";
        $reply[] = " 1.   Panduan registrasi di Gomodo";
        $reply[] = " 2.   Panduan membuat Produk";
        $reply[] = " 3.   Panduan untuk memulai menerima Booking Online dan Pembayaran Non-Tunai";
        $reply[] = " 4.   Panduan untuk membuat tagihan elektronik";
        $reply[] = " 5.   Panduan untuk pemasaran digital";
        $reply[] = " 6.   Panduan untuk distribusi";
        $reply[] = " 99.  Kembali";
        $aboutMenu->content = sprintf('%s', implode("\n", $reply));
        $aboutMenu->save();
        $aboutMenu->keywords()->createMany([
            [
                'keyword' => '2',
            ],
            [
                'keyword' => 'tentang'
            ],
            [
                'keyword' => 'tentang gomodo'
            ]
        ]);

        $registerMenu = new MenuBot();
        $registerMenu->parent_id = $aboutMenu->id;
        $registerMenu->slug = 'register';
        $reply = [];
        $reply[] = "Website gratis yang sudah dilengkapi dengan sistem booking dan pembayaran online";
        $reply[] = "Anda bisa melakukan registrsi dengan mengakses link berikut: ";
        $reply[] = "https://s.id/DAFTARGomodo";
        $reply[] = "";
        $reply[] = " 99.  Kembali";
        $registerMenu->content = sprintf('%s', implode("\n", $reply));
        $registerMenu->save();
        $registerMenu->keywords()->createMany([
            [
                'keyword' => '1',
            ],
            [
                'keyword' => 'register'
            ]
        ]);


        $productMenu = new MenuBot();
        $productMenu->parent_id = $aboutMenu->id;
        $productMenu->slug = 'product';
        $reply = [];
        $reply[] = "Cara Membuat Produk :";
        $reply[] = "*Bagi Penyedia Wisata*";
        $reply[] = "https://s.id/ProdukWisataGomodo";
        $reply[] = "*Bagi Penyedia Jasa Non-Wisata*";
        $reply[] = "https://s.id/ProdukNonwisataGomodo";
        $reply[] = "";
        $reply[] = " 99.  Kembali";
        $productMenu->content = sprintf('%s', implode("\n", $reply));
        $productMenu->save();
        $productMenu->keywords()->createMany([
            [
                'keyword' => '2',
            ],
            [
                'keyword' => 'produk'
            ]
        ]);

        $bookingMenu = new MenuBot();
        $bookingMenu->parent_id = $aboutMenu->id;
        $bookingMenu->slug = 'booking';
        $reply = [];
        $reply[] = "Sistem yang dapat memudahkan customer untuk memesan produk jasa Anda secara instan.";
        $reply[] = "Taukah Anda? Dunia sudah meninggalkan pembayaran dengan uang tunai, jadi Anda harus beradaptasi untuk bertahan dan terus dapat bersaing dengan pasar. Jangan mau ketinggalan. Customer semakin memilih opsi pembayaran non-tunai, jika Anda tidak bisa menerima pembayaran non-tunai, Customer akan memlihih  Merchant atau Pebisnis yang bisa.";
        $reply[] = "Segera upgrade bisnismu supaya dapat menerima online booking  dengan mengikuti panduan di link berikut: ";
        $reply[] = "https://s.id/apaituGomodo";
        $reply[] = "https://s.id/update1-4-1";
        $reply[] = "";
        $reply[] = " 99.  Kembali";
        $bookingMenu->content = sprintf('%s', implode("\n", $reply));
        $bookingMenu->save();
        $bookingMenu->keywords()->createMany([
            [
                'keyword' => '3',
            ],
            [
                'keyword' => 'booking'
            ],
            [
                'keyword' => 'pemesanan'
            ],
            [
                'keyword' => 'pembayaran'
            ]
        ]);

        $eInvoiceMenu = new MenuBot();
        $eInvoiceMenu->parent_id = $aboutMenu->id;
        $eInvoiceMenu->slug = 'e-invoice';
        $reply = [];
        $reply[] = "Fitur yang dapat memudahkan Anda untuk mengirim tagihan kepada customer secara mudah dan instan";
        $reply[] = "Anda bisa melihat informasi detail dengan mengakses link berikut:";
        $reply[] = "https://s.id/GomodoEinvoice";
        $reply[] = "";
        $reply[] = " 99.  Kembali";
        $eInvoiceMenu->content = sprintf('%s', implode("\n", $reply));
        $eInvoiceMenu->save();
        $eInvoiceMenu->keywords()->createMany([
            [
                'keyword' => '4',
            ],
            [
                'keyword' => 'e-invoice'
            ],
            [
                'keyword' => 'e invoice'
            ],
            [
                'keyword' => 'einvoice'
            ]
        ]);

        $eInvoiceMenu = new MenuBot();
        $eInvoiceMenu->parent_id = $aboutMenu->id;
        $eInvoiceMenu->slug = 'marketing-solution';
        $reply = [];
        $reply[] = "Layanan Promosi lengkap yang dapat membantu untuk meningkatkan penjualan produk jasa Anda secara online maupun offline";
        $reply[] = "Anda bisa melihat informasi detail dengan mengakses link berikut:";
        $reply[] = "*Facebook Ads*";
        $reply[] = "https://s.id/FbadsGomodo";
        $reply[] = "*Instagram Ads*";
        $reply[] = "https://s.id/IGadsGomodo";
        $reply[] = "*Google Ads*";
        $reply[] = "hhttps://s.id/GoogleadsGomodo";
        $reply[] = "";
        $reply[] = " 99.  Kembali";
        $eInvoiceMenu->content = sprintf('%s', implode("\n", $reply));
        $eInvoiceMenu->save();
        $eInvoiceMenu->keywords()->createMany([
            [
                'keyword' => '5',
            ],
            [
                'keyword' => 'pemasaran'
            ],
            [
                'keyword' => 'pemasaran digital'
            ]
        ]);

        $eInvoiceMenu = new MenuBot();
        $eInvoiceMenu->parent_id = $aboutMenu->id;
        $eInvoiceMenu->slug = 'distribution';
        $reply = [];
        $reply[] = "Pernahkah penasaran akan gimana caranya untuk menjual di berbagai online travel agent? Sekarang Gomodo dapat membantu memasarkan produk jasa wisata anda di berbagai online platform reseller sekaligus.";
        $reply[] = "Anda bisa melihat informasi detail dengan mengakses link berikut:";
        $reply[] = "https://s.id/GDSGomodo";
        $reply[] = "";
        $reply[] = " 99.  Kembali";
        $eInvoiceMenu->content = sprintf('%s', implode("\n", $reply));
        $eInvoiceMenu->save();
        $eInvoiceMenu->keywords()->createMany([
            [
                'keyword' => '6',
            ],
            [
                'keyword' => 'distribusi'
            ]
        ]);
        $whitelistst = WoowaContact::firstOrCreate([
           'phone'=>'+6281111111111'
        ]);
        $whitelistst->whitelist_until=\Carbon\Carbon::now()->addYears(10)->toDateTimeString();
        $whitelistst->save();

    }
}
