<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TypeFinance;

class UpdateFinanceDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        TypeFinance::where('title_id', 'Pinjaman Dana')
            ->update([
                'content_id'    => 'Ajukan pinjaman dana mulai dari Rp 10.000.000 sebagai modal tambahan untuk mengembangkan bisnis anda.',
                'content_en'    => 'Request loan funds with a minimum of Rp 10.000.000 for additional funds to grow your business even further.',
                'button_id'     => 'Ajukan Pinjaman',
                'button_en'     => 'Request a Loan'
            ]);
        
        TypeFinance::where('title_id', 'Dana Hibah')
            ->update([
                'content_id'    => 'Fitur Dana Hibah akan segera hadir di Gomodo!',
                'content_en'    => 'Grant Funds feature will be arriving soon at Gomodo!'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('type_finance', function (Blueprint $table) {
            //
        });
    }
}
