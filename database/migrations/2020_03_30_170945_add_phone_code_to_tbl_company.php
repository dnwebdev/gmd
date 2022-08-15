<?php

use App\Models\Company;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneCodeToTblCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('tbl_company', function (Blueprint $table) {
//            $table->integer('phone_code')->nullable();
//        });
        Company::withoutGlobalScopes()->chunk(100, function ($companies) {
            foreach ($companies as $company):
                $company->agent->update(['phone_code' => 62]);
//                $company->update(['phone_code' => 62]);
//                if ($company->phone_company){
//                    if (substr($company->phone_company, 0, 2) == '62'):
//                        $company->update(['phone_company' => str_replace_first('62', '', $company->phone_company)]);
//                    else:
//                        $company->update(['phone_company' => (int)$company->phone_company]);
//                    endif;
//                }
                if ($company->agent->phone && $company->agent->phone != '000000000000'):
                    if (substr($company->agent->phone, 0, 2) == '62'):
                        $company->agent->update(['phone' => str_replace_first('62', '', $company->agent->phone)]);
                    elseif (substr($company->agent->phone, 0, 2) == '00'):
                    else:
                        $company->agent->update(['phone' => (int)$company->agent->phone]);
                    endif;
                endif;
            endforeach;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('tbl_company', function (Blueprint $table) {
//            $table->dropColumn('phone_code');
//        });
    }
}
